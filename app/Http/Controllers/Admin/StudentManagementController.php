<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\BatchService;
use App\Services\StudentService;
use App\Services\DownloadService;
use Illuminate\Support\Facades\DB;
use App\Services\DepartmentService;
use App\Services\ImportDataService;
use App\Helpers\PasswordCheckHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Services\RegistrationService;
use App\Http\Requests\ImportFileRequest;
use Flasher\Toastr\Laravel\Facade\Toastr;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Validation\ValidationException;

class StudentManagementController extends Controller
{
    protected $studentService,
        $batchService,
        $departmentService,
        $userService,
        $registrationService,
        $downloadService,
        $importDataService;

    // Constructor Injection
    public function __construct(
        StudentService $studentService,
        BatchService $batchService,
        DepartmentService $departmentService,
        UserService $userService,
        RegistrationService $registrationService,
        DownloadService $downloadService,
        ImportDataService $importDataService,
    ) {
        $this->studentService = $studentService;
        $this->batchService = $batchService;
        $this->departmentService = $departmentService;
        $this->userService = $userService;
        $this->registrationService = $registrationService;
        $this->downloadService = $downloadService;
        $this->importDataService = $importDataService;
    }

    public function index(Request $request)
    {
        $batch_id = $this->batchService->getRelevantBatch($request->batch);
        
        $lastYear = $this->studentService->getLastYearStudent()->year;
        $year = $request->year ?? ($currentBatch->year ?? $lastYear);

        // table filters
        $departmentData = $this->departmentService->getAllDepartment();
        $batchData = $this->batchService->getAllBatch('');
        $yearData = $this->studentService->getStudentYear();
        $filters = [
            'year' => $year,
            'batch_id' => $batch_id,
            'department' => $request->department ?? '',
            'status' => $request->status ?? '',
            'search' => $request->searchKeyword ?? '',
        ];

        // table data
        $data = $this->studentService->getStudent($filters);

        foreach ($data as $dt) {
            $registration = $this->registrationService->getRegistrationByStudentId($batch_id, $dt->id);
            if ($registration != null) {
                $dt->status = $registration->status == '2' ? 'Belum Terdaftar' : 'Terdaftar';
            } else {
                $dt->status = 'Belum Terdaftar';
            }
        }

        // card data
        $registeredStudent = $this->studentService->getStudentByStatusCount($year, $batch_id, 'registered');
        $unregisteredStudent = $this->studentService->getStudentByStatusCount($year, $batch_id, 'unregistered');

        return view('pages.admin.student', [
            'data' => $data,
            'registeredStudent' => $registeredStudent,
            'unregisteredStudent' => $unregisteredStudent,
            'batchData' => $batchData,
            'yearData' => $yearData,
            'departmentData' => $departmentData,
            'filters' => $filters,
            'pages' => 'studentManagement',
        ]);
    }

    public function store(StoreStudentRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['password'] = PasswordCheckHelper::handlePassword($request->password, $request->check_password);
            $validatedData['role'] = 'student';

            DB::transaction(function () use ($validatedData) {
                $newUser = $this->userService->addUser($validatedData);

                $this->studentService->addStudent([
                    'user_id' => $newUser->id,
                    'name' => $validatedData['name'],
                    'nisn' => $validatedData['nisn'],
                    'nis' => $validatedData['nis'],
                    'gender' => $validatedData['gender'],
                    'department_id' => $validatedData['department_id'],
                    'year' => $validatedData['year'],
                    'phone_num' => $validatedData['phone_num'],
                ]);
            });
            Toastr::addSuccess('Data siswa berhasil ditambah!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Toastr::addError($e->errors()['password'][0]);
        } catch (\Exception $e) {
            Toastr::addError('Data siswa gagal ditambah!');
        }
        return redirect()->back();
    }

    public function update(UpdateStudentRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();

            if (!empty($validatedData['password'])) {
                $validatedData['password'] = PasswordCheckHelper::handlePassword($request->password, $request->check_password);
            }

            DB::transaction(function () use ($id, $validatedData) {
                $this->studentService->updateStudent($id, [
                    'name' => $validatedData['name'],
                    'nisn' => $validatedData['nisn'],
                    'nis' => $validatedData['nis'],
                    'gender' => $validatedData['gender'],
                    'department_id' => $validatedData['department_id'],
                    'year' => $validatedData['year'],
                    'phone_num' => $validatedData['phone_num'],
                ]);

                $updateUserData = [
                    'username' => $validatedData['username'],
                    'email' => $validatedData['email'],
                ];

                if (isset($validatedData['password'])) {
                    $updateUserData['password'] = $validatedData['password'];
                }

                $this->userService->updateUser($validatedData['user_id'], $updateUserData);
            });
            Toastr::addSuccess('Data siswa berhasil diubah!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Toastr::addError($e->errors()['password'][0]);
        } catch (\Exception $e) {
            Toastr::addError('Data siswa gagal diubah!');
        }
        return redirect()->back();
    }

    public function destroy($id)
    {
        $user_id = $this->studentService->getStudentById($id)->user_id;
        try {
            $this->userService->deleteUser($user_id);
            Toastr::addSuccess('Data siswa berhasil dihapus!');
        } catch (\Exception $e) {
            Toastr::addError('Data siswa gagal dihapus!');
        }
        return redirect()->back();
    }

    public function import(ImportFileRequest $request)
    {
        $request->validated();

        $file = $request->file('import_file');

        try {
            $validData = $this->importDataService->importStudentDataCheck($file);
        } catch (ValidationException $e) {
            Toastr::addError(nl2br($e->getMessage()));
            return redirect()->back();
        }

        try {
            DB::transaction(function () use ($validData) {
                foreach ($validData as $data) {
                    $data['password'] = Hash::make($data['password']);
                    $data['role'] = 'student';
                    $data['gender'] = match ($data['gender']) {
                        'Laki-Laki' => 'men',
                        'Perempuan' => 'women',
                        default => 'men',
                    };
                    $data['department'] = match ($data['department']) {
                        'RPL' => '1',
                        'DPIB' => '2',
                        default => '3',
                    };

                    $newUser = $this->userService->addUser($data);

                    $studentData = [
                        'user_id' => $newUser->id ?? '',
                        'name' => $data['name'],
                        'nisn' => $data['nisn'],
                        'nis' => $data['nis'],
                        'gender' => $data['gender'],
                        'department_id' => $data['department_id'],
                        'year' => $data['year'],
                        'phone_num' => $data['phone_num'],
                    ];

                    $this->studentService->addStudent($studentData);
                }
            });
            Toastr::addSuccess('Impor data siswa berhasil!');
        } catch (\Exception $e) {
            Toastr::addError('Impor data siswa gagal!');
        }
        return redirect()->back();
    }

    public function downloadTemplateFile()
    {
        return $this->downloadService->templateImportDataDownload('template_import_student.xlsx');
    }
}
