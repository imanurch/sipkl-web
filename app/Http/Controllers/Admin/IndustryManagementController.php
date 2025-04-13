<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\IndustryService;
use Illuminate\Support\Facades\DB;
use App\Services\DepartmentService;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Flasher\Toastr\Laravel\Facade\Toastr;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class IndustryManagementController extends Controller
{
    protected $industryService, $batchService, $departmentService;

    // Constructor Injection
    public function __construct(IndustryService $industryService, BatchService $batchService, DepartmentService $departmentService)
    {
        $this->industryService = $industryService;
        $this->batchService = $batchService;
        $this->departmentService = $departmentService;
    }

    public function index(Request $request)
    {
        $activeTab = $request->query('tab', 'partner');
        // dd($request->all());

        // batch data
        $current_batch = $this->batchService->getBatchByStatus('active');
        if ($current_batch != null) {
            $batch_id = $current_batch->id;
        } else {
            $batch_id = '';
        }

        // table filters
        $filters = [
            'unconfirmedIndustrySearch' => $request->unconfirmedSearchKeyword ?? '',
            'partnerIndustrySearch' => $request->partnerSearchKeyword ?? '',
            'rejectedIndustrySearch' => $request->rejectedSearchKeyword ?? '',
            'status' => $request->status ?? '',
        ];

        // data table
        $unconfirmedData = $this->industryService->getUnconfirmedIndustry($filters);
        $partnerData = $this->industryService->getPartnerIndustry($filters, $batch_id);
        $rejectedData = $this->industryService->getRejectedIndustry($filters);

        // card data
        $activeIndustry = $this->industryService->getIndustryByStatusCount($batch_id, 'active');
        $inactiveIndustry = $this->industryService->getIndustryByStatusCount($batch_id, 'inactive');
        $unconfirmedIndustry = $this->industryService->getIndustryByConfirmStatusCount('unconfirmed');
        $partnerIndustry = $this->industryService->getIndustryByConfirmStatusCount('partner');
        $rejectedIndustry = $this->industryService->getIndustryByConfirmStatusCount('rejected');

        return view('pages.admin.industry', [
            'unconfirmedIndustryData' => $unconfirmedData,
            'partnerIndustryData' => $partnerData,
            'rejectedIndustryData' => $rejectedData,
            'activeIndustry' => $activeIndustry,
            'inactiveIndustry' => $inactiveIndustry,
            'unconfirmedIndustry' => $unconfirmedIndustry,
            'partnerIndustry' => $partnerIndustry,
            'rejectedIndustry' => $rejectedIndustry,
            'filters' => $filters,
            'activeTab' => $activeTab,
            'pages' => 'industryManagement',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->except(['_token']);
        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'address' => 'required|string',
                'email' => 'required|unique:industries,email|email',
                'phone_num' => 'required|unique:industries,phone_num|string|min:10|max:14',
                'leader_name' => 'required|string',
            ]);
            $validatedData['status'] = '1';

            $this->industryService->addIndustry($validatedData);
            Toastr::addSuccess('Data industri berhasil ditambah!');
        } catch (\Exception $e) {
            Toastr::addError('Data industri gagal ditambah!');
        }
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $data = $request->except(['_token', '_method']);

        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'address' => 'required|string',
                'email' => 'required|email|unique:industries,email,' . $id,
                'phone_num' => 'required|string|min:10|max:14|unique:industries,phone_num,' . $id,
                'leader_name' => 'required|string',
                'status' => 'nullable|string|min:10|max:14|unique:industries,phone_num,' . $id,
            ]);

            $this->industryService->updateIndustry($id, $validatedData);
            Toastr::addSuccess('Data industri berhasil diubah!');
        } catch (\Exception $e) {
            Toastr::addError('Data industri gagal diubah!');
        }
        return back();
    }

    public function confirmStatusIndustry($industryId, $status)
    {
        try {
            $this->industryService->updateIndustryRequestStatus($industryId, $status);
            Toastr::addSuccess('Pengajuan industri berhasil dikonfirmasi!');
        } catch (\Exception $e) {
            Toastr::addError('Pengajuan industri gagal dikonfirmasi!');
        }
        return back();
    }

    public function updateStatusIndustry($id, Request $request)
    {
        // dd($id, $request->all());
        if($request->status == 'reject'){
            return back();
        }
        try {
            $this->industryService->updateIndustryRequestStatus($id, $request->status);
            Toastr::addSuccess('Status industry berhasil diperbarui!');
        } catch (\Exception $e) {
            Toastr::addError('Status industri gagal diperbarui!');
        }
        return back();
    }

    public function destroy($id)
    {
        try {
            $this->industryService->deleteIndustry($id);
            Toastr::addSuccess('Data industri berhasil dihapus!');
        } catch (\Exception $e) {
            Toastr::addError('Data industri gagal dihapus!');
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
                    if ($row[1] == null) break;

                    $validatedData = $request->validate([
                        'name' => $row[1],
                        'address' => $row[2],
                        'email' => $row[3],
                        'phone_num' => $row[4],
                        'leader_name' => $row[5],
                    ], [
                        'name' => 'required|string',
                        'address' => 'required|string',
                        'email' => 'required|unique:industries,email|email',
                        'phone_num' => 'required|unique:industries,phone_num|string|min:10|max:14',
                        'leader_name' => 'required|string',
                    ]);

                    $data = [
                        'name' => $row[1],
                        'address' => $row[2],
                        'email' => $row[3],
                        'phone_num' => $row[4],
                        'leader_name' => $row[5],
                        'status' => '1',
                    ];

                    $this->industryService->addIndustry($data);
                }
            });
            Toastr::addSuccess('Impor data industri berhasil!');
        } catch (\Exception $e) {
            Toastr::addError('Impor data industri gagal!');
        }
        return redirect()->back();
    }

    public function downloadTemplateFile()
    {
        $filePath = storage_path('app/public/files/template_import_industry.xlsx');

        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            Toastr::addError('File tidak ditemukan');
        }
    }

    public function export(Request $request)
    {
        // dd($request->all());
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'NAMA');
        $sheet->setCellValue('C1', 'ALAMAT');
        $sheet->setCellValue('D1', 'EMAIL');
        $sheet->setCellValue('E1', 'NOMOR TELEPON');
        $sheet->setCellValue('F1', 'NAMA PIMPINAN');

        $current_batch = $this->batchService->getBatchByStatus('active');
        $batch_id = $current_batch != null ? $current_batch->id : $batch_id = '';

        $data = $request->data_type == 'Semua' ? $this->industryService->getPartnerIndustryList() : $this->industryService->getActivePartnerIndustryList($batch_id);

        $row = 2;
        $num = 1;
        foreach ($data as $dt) {
            $sheet->setCellValue('A' . $row, $num);
            $sheet->setCellValue('B' . $row, $dt->name);
            $sheet->setCellValue('C' . $row, $dt->address);
            $sheet->setCellValue('F' . $row, $dt->email);
            $sheet->setCellValue('G' . $row, $dt->phone_num);
            $sheet->setCellValue('D' . $row, $dt->leader_name);
            $row++;
            $num++;
        }

        $filename = "data_industry_export.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
