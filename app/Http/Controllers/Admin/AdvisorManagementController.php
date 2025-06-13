<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\BatchService;
use App\Services\AdvisorService;
use App\Services\DownloadService;
use Illuminate\Support\Facades\DB;
use App\Services\DepartmentService;
use App\Services\ImportDataService;
use App\Helpers\PasswordCheckHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Services\AdvisorLevelService;
use App\Http\Requests\ImportFileRequest;
use App\Services\AdvisorPositionService;
use Flasher\Toastr\Laravel\Facade\Toastr;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Http\Requests\StoreAdvisorRequest;
use App\Http\Requests\UpdateAdvisorRequest;
use Illuminate\Validation\ValidationException;

class AdvisorManagementController extends Controller
{
    protected
        $advisorService,
        $batchService,
        $departmentService,
        $userService,
        $advisorPositionService,
        $advisorLevelService,
        $downloadService,
        $importDataService;

    // Constructor Injection
    public function __construct(
        AdvisorService $advisorService,
        BatchService $batchService,
        DepartmentService $departmentService,
        UserService $userService,
        AdvisorPositionService $advisorPositionService,
        AdvisorLevelService $advisorLevelService,
        DownloadService $downloadService,
        ImportDataService $importDataService,
    ) {
        $this->advisorService = $advisorService;
        $this->batchService = $batchService;
        $this->departmentService = $departmentService;
        $this->userService = $userService;
        $this->advisorPositionService = $advisorPositionService;
        $this->advisorLevelService = $advisorLevelService;
        $this->downloadService = $downloadService;
        $this->importDataService = $importDataService;
    }

    /**
     * Display the list of advisors with filters applied.
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        $batch_id = $this->batchService->getRelevantBatch($request->batch);

        // table filters
        $departmentData = $this->departmentService->getAllDepartment();
        $batchData = $this->batchService->getAllBatch('');
        $positionData = $this->advisorPositionService->getAllPosition();
        $levelData = $this->advisorLevelService->getAllLevel();

        $filters = [
            'batch_id' => $batch_id,
            'department' => $request->department ?? '',
            'status' => $request->status ?? '',
            'search' => $request->searchKeyword ?? '',
        ];

        // table data
        $data = $this->advisorService->getAdvisor($filters);

        // card data
        $activeAdvisor = $this->advisorService->getAdvisorByStatusCount($batch_id, 'active');
        $inactiveAdvisor = $this->advisorService->getAdvisorByStatusCount($batch_id, 'inactive');

        return view('pages.admin.advisor', [
            'data' => $data,
            'activeAdvisor' => $activeAdvisor,
            'inactiveAdvisor' => $inactiveAdvisor,
            'batchData' => $batchData,
            'positionData' => $positionData,
            'levelData' => $levelData,
            'departmentData' => $departmentData,
            'filters' => $filters,
            'pages' => 'advisorManagement',
        ]);
    }

    /**
     * Store a new advisor record in the database.
     *
     * @param StoreAdvisorRequest $request
     */
    public function store(StoreAdvisorRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['password'] = PasswordCheckHelper::handlePassword($request->password, $request->check_password);

            DB::transaction(function () use ($validatedData) {
                $validatedData['role'] = 'advisor';
                $newUser = $this->userService->addUser($validatedData);

                $this->advisorService->addAdvisor([
                    'user_id' => $newUser->id,
                    'name' => $validatedData['name'],
                    'nip' => $validatedData['nip'],
                    'department_id' => $validatedData['department_id'],
                    'phone_num' => $validatedData['phone_num'],
                    'position_id' => $validatedData['position_id'],
                    'level_id' => $validatedData['level_id'],
                ]);
            });
            Toastr::addSuccess('Data guru pembimbing berhasil ditambah!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Toastr::addError($e->errors()['password'][0]);
        } catch (\Exception $e) {
            Toastr::addError('Data guru pembimbing gagal ditambah!');
        }
        return redirect()->back();
    }

    /**
     * Update the existing advisor record.
     *
     * @param UpdateAdvisorRequest $request
     * @param int $id
     */
    public function update(UpdateAdvisorRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();

            if (!empty($validatedData['password'])) {
                $validatedData['password'] = PasswordCheckHelper::handlePassword($request->password, $request->check_password);
            }

            DB::transaction(function () use ($id, $validatedData) {
                $this->advisorService->updateAdvisor($id, [
                    'name' => $validatedData['name'],
                    'nip' => $validatedData['nip'],
                    'position_id' => $validatedData['position_id'],
                    'level_id' => $validatedData['level_id'],
                    'department_id' => $validatedData['department_id'],
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
            Toastr::addSuccess('Data guru pembimbing berhasil diubah!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Toastr::addError($e->errors()['password'][0]);
        } catch (\Exception $e) {
            Toastr::addError('Data guru pembimbing gagal diubah!');
        }
        return redirect()->back();
    }

    /**
     * Delete the specified advisor record from the database.
     *
     * @param int $id
     */
    public function destroy($id)
    {
        $user_id = $this->advisorService->getAdvisorById($id)->user_id;

        try {
            $this->userService->deleteUser($user_id);
            Toastr::addSuccess('Data guru pembimbing berhasil dihapus!');
        } catch (\Exception $e) {
            Toastr::addError('Data guru pembimbing gagal dihapus!');
        }
        return redirect()->back();
    }

    /**
     * Import advisor data from the uploaded file and store them in the database.
     *
     * @param ImportFileRequest $request
     */
    public function import(ImportFileRequest $request)
    {
        $request->validated();

        $file = $request->file('import_file');

        try {
            $validData = $this->importDataService->importAdvisorDataCheck($file);
        } catch (ValidationException $e) {
            Toastr::addError(nl2br($e->getMessage()));
            return redirect()->back();
        }

        try {
            DB::transaction(function () use ($validData) {
                foreach ($validData as $data) {
                    $newUser = $this->userService->addUser($data);
                    $data['password'] = Hash::make($data['password']);

                    $advisorData = [
                        'user_id' => $newUser->id ?? '',
                        'name' => $data['name'],
                        'nip' => $data['nip'],
                        'department_id' => $data['department_id'],
                        'position_id' => $data['position_id'],
                        'level_id' => $data['level_id'],
                        'phone_num' => $data['phone_num'],
                    ];

                    $this->advisorService->addAdvisor($advisorData);
                }
            });
            Toastr::addSuccess('Impor data guru berhasil!');
        } catch (\Exception $e) {
            Toastr::addError('Impor data guru gagal!');
        }
        return redirect()->back();
    }

    /**
     * Download the template for importing advisor data.
     */
    public function downloadTemplateFile()
    {
        return $this->downloadService->templateImportDataDownload('template_import_advisor.xlsx');
    }

    /**
     * Export advisor data to an Excel file based on the given filters.
     *
     * @param Request $request
     */
    public function export(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'NAMA');
        $sheet->setCellValue('C1', 'NIP');
        $sheet->setCellValue('D1', 'JURUSAN');
        $sheet->setCellValue('E1', 'JABATAN');
        $sheet->setCellValue('F1', 'PANGKAT/GOL');
        $sheet->setCellValue('G1', 'USERNAME');
        $sheet->setCellValue('H1', 'EMAIL');
        $sheet->setCellValue('I1', 'NOMOR TELEPON');

        $current_batch = $this->batchService->getBatchByStatus('active');
        $batch_id = $current_batch != null ? $current_batch->id : '';

        $data = $request->data_type == 'Semua' ? $this->advisorService->getAdvisorList() : $this->advisorService->getActiveAdvisorList($batch_id);

        $row = 2;
        $num = 1;
        foreach ($data as $dt) {
            $sheet->setCellValue('A' . $row, $num);
            $sheet->setCellValue('B' . $row, $dt->name);
            $sheet->setCellValue('C' . $row, $dt->nip);
            $sheet->setCellValue('D' . $row, $dt->department->name);
            $sheet->setCellValue('E' . $row, $dt->advisorPosition->name);
            $sheet->setCellValue('F' . $row, $dt->advisorLevel->name);
            $sheet->setCellValue('G' . $row, $dt->user->username);
            $sheet->setCellValue('H' . $row, $dt->user->email);
            $sheet->setCellValue('I' . $row, $dt->phone_num);
            $row++;
            $num++;
        }

        $filename = "data_advisor_export.xlsx";
        try {
            return response()->streamDownload(function () use ($spreadsheet) {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            }, $filename);
        } catch (\Exception $e) {
            Toastr::addError('Export gagal!');
            return redirect()->back();
        }
    }
}
