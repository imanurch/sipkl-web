<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\BatchService;
use App\Services\AdvisorService;
use Illuminate\Support\Facades\DB;
use App\Services\DepartmentService;
use App\Http\Controllers\Controller;
use App\Services\AdvisorLevelService;
use App\Services\AdvisorPositionService;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Flasher\Toastr\Laravel\Facade\Toastr;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdvisorManagementController extends Controller
{
    protected
        $advisorService,
        $batchService,
        $departmentService,
        $userService,
        $advisorPositionService,
        $advisorLevelService;

    // Constructor Injection
    public function __construct(
        AdvisorService $advisorService,
        BatchService $batchService,
        DepartmentService $departmentService,
        UserService $userService,
        AdvisorPositionService $advisorPositionService,
        AdvisorLevelService $advisorLevelService
    ) {
        $this->advisorService = $advisorService;
        $this->batchService = $batchService;
        $this->departmentService = $departmentService;
        $this->userService = $userService;
        $this->advisorPositionService = $advisorPositionService;
        $this->advisorLevelService = $advisorLevelService;
    }

    public function index(Request $request)
    {
        // batch data
        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $request->batch ?? ($currentBatch->id ?? '');

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

    public function store(Request $request)
    {
        if ($request->check_password !== $request->password) {
            Toastr::addError('Password tidak konsisten!');
            return redirect()->back();
        }
        $data = $request->except(['_token']);

        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'nip' => 'required|size:18',
                'position_id' => 'required',
                'level_id' => 'required',
                'department_id' => 'required',
                'username' => 'required|string',
                'email' => 'required|unique:users,email|email',
                'phone_num' => 'required|unique:advisors,phone_num|string|min:10|max:14',
                'password' => 'required|string|min:8|max:12',
            ]);
            $validatedData['password'] = Hash::make($validatedData['password']);
            $validatedData['department_id'] = $validatedData['department_id'] == 'RPL' ? '1' : ($validatedData['department_id'] == 'DPIB' ? '2' : ($validatedData['department_id'] == 'K3R' ? '3' : ''));

            DB::transaction(function () use ($validatedData) {
                $newUser = $this->userService->addUser([
                    'username' => $validatedData['username'],
                    'email' => $validatedData['email'],
                    'password' => $validatedData['password'],
                    'role' => 'advisor',
                ]);
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
        } catch (\Exception $e) {
            Toastr::addError('Data guru pembimbing gagal ditambah!');
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
                'nip' => 'required|size:18',
                'position_id' => 'required',
                'level_id' => 'required',
                'department_id' => 'required',
                'username' => 'required|string',
                'email' => 'required|email|unique:users,email,' . $request->input('user_id'),
                'phone_num' => 'required|string|min:10|max:14|unique:advisors,phone_num,' . $id,
                'password' => 'nullable|string|min:8|max:12',
            ]);

            if (!empty($validatedData['password'])) {
                if ($request->check_password !== $request->password) {
                    Toastr::addError('Password tidak konsisten!');
                    return redirect()->back();
                }
                $validatedData['password'] = Hash::make($validatedData['password']);
            }

            $validatedData['department_id'] = $validatedData['department_id'] == 'RPL' ? '1' : ($validatedData['department_id'] == 'DPIB' ? '2' : ($validatedData['department_id'] == 'K3R' ? '3' : ''));
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
        } catch (\Exception $e) {
            Toastr::addError('Data guru pembimbing gagal diubah!');
        }
        return redirect()->back();
    }

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

    public function import(Request $request)
    {
        $validatedData = $request->validate([
            'import_file' => 'required|mimes:xlsx,xml,xls',
        ]);

        $file = $request->file('import_file');

        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getSheetByName('Data');
        $rows = $worksheet->toArray();

        try {
            DB::transaction(function () use ($rows, $request) {
                foreach ($rows as $index => $row) {
                    if ($index == 0) continue;
                    if ($row[1] == null) break;

                    $validatedData = $request->validate([
                        'name' => $row[1],
                        'nip' => $row[2],
                        'department_id' => $row[3],
                        'position_id' => $row[4],
                        'level_id' => $row[5],
                        'username' => $row[6],
                        'email' => $row[7],
                        'phone_num' => $row[8],
                        'password' => $row[9],
                    ], [
                        'name' => 'required|string',
                        'nip' => 'required|size:18|unique:advisors,nip',
                        'department_id' => 'required',
                        'position_id' => 'required',
                        'level_id' => 'required',
                        'username' => 'required|string',
                        'email' => 'required|email|unique:users,email',
                        'phone_num' => 'required|string|min:10|max:14|unique:advisors,phone_num',
                        'password' => 'required|string|min:8|max:12',
                    ]);


                    $row[3] = $row[3] == 'RPL' ? '1' : ($row[3] == 'DPIB' ? '2' : ($row[3] == 'K3R' ? '3' : ''));
                    $row[4] = $row[4] == 'Guru Pertama' ? '1' : ($row[4] == 'Guru Muda' ? '2' : ($row[4] == 'Guru Madya' ? '3' : ($row[4] == 'Guru Utama' ? '4' : '')));
                    $row[5] = $row[5] == 'I/a' ? '1' : ($row[5] == 'I/b' ? '2' : ($row[5] == 'I/c' ? '3' : ($row[5] == 'I/d' ? '4' : ($row[5] == 'II/a' ? '5' : ($row[5] == 'II/b' ? '6' : ($row[5] == 'II/c' ? '7' : ($row[5] == 'II/d' ? '8' : ($row[5] == 'III/a' ? '9' : ($row[5] == 'III/b' ? '10' : ($row[5] == 'III/c' ? '11' : ($row[5] == 'III/d' ? '12' : ($row[5] == 'IV/a' ? '13' : ($row[5] == 'IV/b' ? '14' : ($row[5] == 'IV/c' ? '15' : ($row[5] == 'IV/d' ? '16' : ($row[5] == 'IV/e' ? '17' : ''))))))))))))))));
                    $row[9] = Hash::make($row[9]);

                    // dd($row);

                    $userData = [
                        'username' => $row[6],
                        'email' => $row[7],
                        'password' => $row[9],
                        'role' => 'advisor',
                    ];

                    $newUser = $this->userService->addUser($userData);

                    $advisorData = [
                        'user_id' => $newUser->id ?? '',
                        'name' => $row[1],
                        'nip' => $row[2],
                        'department_id' => $row[3],
                        'position_id' => $row[4],
                        'level_id' => $row[5],
                        'phone_num' => $row[8],
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

    public function downloadTemplateFile()
    {
        $filePath = storage_path('app/public/files/template_import_advisor.xlsx');

        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            Toastr::addError('File tidak ditemukan');
        }
    }

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
        $batch_id = $current_batch != null ? $current_batch->id : $batch_id = '';

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
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
