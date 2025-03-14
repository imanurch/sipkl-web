<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\BatchService;
use App\Services\StudentService;
use Illuminate\Support\Facades\DB;
use App\Services\DepartmentService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Services\RegistrationService;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Flasher\Toastr\Laravel\Facade\Toastr;

class StudentManagementController extends Controller
{
    protected $studentService,
        $batchService,
        $departmentService,
        $userService,
        $registrationService;

    // Constructor Injection
    public function __construct(
        StudentService $studentService,
        BatchService $batchService,
        DepartmentService $departmentService,
        UserService $userService,
        RegistrationService $registrationService
    ) {
        $this->studentService = $studentService;
        $this->batchService = $batchService;
        $this->departmentService = $departmentService;
        $this->userService = $userService;
        $this->registrationService = $registrationService;
    }

    public function index(Request $request)
    {
        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $request->batch ?? ($currentBatch->id ?? '');

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

    public function store(Request $request)
    {
        if ($request->check_password !== $request->password) {
            return back()->withErrors(['password' => 'Passwords do not match.']);
        }
        $data = $request->except(['_token']);

        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'nisn' => 'required|size:10|unique:students,nisn',
                'gender' => 'required',
                'department_id' => 'required',
                'year' => 'required',
                'username' => 'required',
                'email' => 'required|unique:users,email',
                'phone_num' => 'required|string|min:10|max:14|unique:students,phone_num,',
                'password' => 'required|string|size:8',
            ]);

            $validatedData['password'] = Hash::make($validatedData['password']);
            $validatedData['department_id'] = $validatedData['department_id'] == 'K3R' ? '1' : ($validatedData['department_id'] == 'DPIB' ? '2' : ($validatedData['department_id'] == 'RPL' ? '3' : ''));
            $validatedData['gender'] = $validatedData['gender'] == 'Laki-Laki' ? 'men' : 'women';

            DB::transaction(function () use ($validatedData) {
                $newUser = $this->userService->addUser([
                    'username' => $validatedData['username'],
                    'email' => $validatedData['email'],
                    'password' => $validatedData['password'],
                    'role' => 'student',
                ]);
                $this->studentService->addStudent([
                    'user_id' => $newUser->id,
                    'name' => $validatedData['name'],
                    'nisn' => $validatedData['nisn'],
                    'gender' => $validatedData['gender'],
                    'department_id' => $validatedData['department_id'],
                    'year' => $validatedData['year'],
                    'phone_num' => $validatedData['phone_num'],
                ]);
            });
            Toastr::addSuccess('Data siswa berhasil ditambah!');
        } catch (\Exception $e) {
            Toastr::addError('Data siswa gagal ditambah!');
        }
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $data = $request->except(['_token', '_method']);

        try {
            $validatedData = $request->validate([
                'user_id' => 'required',
                'name' => 'required|string',
                'nisn' => 'required|size:10|unique:students,nisn,' . $id,
                'gender' => 'required',
                'department_id' => 'required',
                'year' => 'required',
                'username' => 'required',
                'email' => 'required|unique:users,email,' . $request->input('user_id'),
                'phone_num' => 'required|string|min:10|max:14|unique:students,phone_num,' . $id,
                'password' => 'nullable|string|size:8',
            ]);

            if (!empty($validatedData['password'])) {
                if ($request->check_password !== $request->password) {
                    return back()->withErrors(['password' => 'Passwords do not match.']);
                }
                $validatedData['password'] = Hash::make($validatedData['password']);
            }

            $validatedData['department_id'] = $validatedData['department_id'] == 'K3R' ? '1' : ($validatedData['department_id'] == 'DPIB' ? '2' : ($validatedData['department_id'] == 'RPL' ? '3' : ''));
            $validatedData['gender'] = $validatedData['gender'] == 'Laki-Laki' ? 'men' : 'women';

            DB::transaction(function () use ($id, $validatedData) {
                $this->studentService->updateStudent($id, [
                    'name' => $validatedData['name'],
                    'nisn' => $validatedData['nisn'],
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

    public function import(Request $request)
    {
        $validatedData = $request->validate([
            'import_file' => 'required|mimes:xlsx,xml,xls',
        ]);

        $file = $request->file('import_file');

        // Load file Excel
        $spreadsheet = IOFactory::load($file->getPathname());
        // $worksheet = $spreadsheet->getActiveSheet();
        $worksheet = $spreadsheet->getSheetByName('Data');
        $rows = $worksheet->toArray();

        try {
            DB::transaction(function () use ($rows, $request) {
                foreach ($rows as $index => $row) {
                    if ($index == 0) continue;

                    $validatedData = $request->validate([
                        'name' => $row[1],
                        'nisn' => $row[2],
                        'gender' => $row[3],
                        'department_id' => $row[4],
                        'year' => $row[5],
                        'username' => $row[6],
                        'email' => $row[7],
                        'phone_num' => $row[8],
                        'password' => $row[9],
                    ], [
                        'name' => 'required|string',
                        'nisn' => 'required|size:10|unique:students,nisn',
                        'gender' => 'required',
                        'department_id' => 'required',
                        'year' => 'required',
                        'username' => 'required',
                        'email' => 'required|unique:users,email',
                        'phone_num' => 'required|string|min:10|max:14|unique:students,phone_num,',
                        'password' => 'required|string|size:8',
                    ]);

                    $row[3] = $row[3] == 'Laki-Laki' ? 'men' : 'women';
                    $row[4] = $row[4] == 'K3R' ? '1' : ($row[4] == 'DPIB' ? '2' : ($row[4] == 'RPL' ? '3' : ''));
                    $row[9] = Hash::make($row[9]);

                    $userData = [
                        'username' => $row[6],
                        'email' => $row[7],
                        'password' => $row[9],
                        'role' => 'student',
                    ];

                    $newUser = $this->userService->addUser($userData);

                    $studentData = [
                        'user_id' => $newUser->id ?? '',
                        'name' => $row[1],
                        'nisn' => $row[2],
                        'gender' => $row[3],
                        'department_id' => $row[4],
                        'year' => $row[5],
                        'phone_num' => $row[8],
                    ];

                    // dd($userData, $studentData);

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
        $filePath = storage_path('app/public/files/template_import_student.xlsx');

        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            Toastr::addError('File tidak ditemukan');
        }
    }
}
