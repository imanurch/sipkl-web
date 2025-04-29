<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\DownloadService;
use App\Services\IndustryService;
use Illuminate\Support\Facades\DB;
use App\Services\ImportDataService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportFileRequest;
use App\Http\Requests\StoreIndustryRequest;
use App\Http\Requests\UpdateIndustryRequest;
use Flasher\Toastr\Laravel\Facade\Toastr;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Validation\ValidationException;

class IndustryManagementController extends Controller
{
    protected $industryService,
        $batchService,
        $downloadService,
        $importDataService;

    // Constructor Injection
    public function __construct(
        IndustryService $industryService,
        BatchService $batchService,
        DownloadService $downloadService,
        ImportDataService $importDataService
    ) {
        $this->industryService = $industryService;
        $this->batchService = $batchService;
        $this->downloadService = $downloadService;
        $this->importDataService = $importDataService;
    }

    /**
     * Display the industry management page with data and filters.
     */
    public function index(Request $request)
    {
        $activeTab = $request->query('tab', 'partner');

        // batch data
        $current_batch = $this->batchService->getBatchByStatus('active');
        $batch_id = $current_batch != null ? $current_batch->id : '';

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

    /**
     * Store a newly created industry record in storage.
     */
    public function store(StoreIndustryRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['status'] = '1';

            $this->industryService->addIndustry($validatedData);
            Toastr::addSuccess('Data industri berhasil ditambah!');
        } catch (\Exception $e) {
            Toastr::addError('Data industri gagal ditambah!');
        }
        return redirect()->back();
    }

    /**
     * Update the specified industry record in storage.
     */
    public function update(UpdateIndustryRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();

            $this->industryService->updateIndustry($id, $validatedData);
            Toastr::addSuccess('Data industri berhasil diubah!');
        } catch (\Exception $e) {
            Toastr::addError('Data industri gagal diubah!');
        }
        return back();
    }

    /**
     * Confirm or reject an industry registration request.
     */
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

    /**
     * Update the status of the specified industry.
     */
    public function updateStatusIndustry($id, Request $request)
    {
        if ($request->status == 'reject') {
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

    /**
     * Remove the specified industry from storage.
     */
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

    /**
     * Import industry data from an uploaded file.
     */
    public function import(ImportFileRequest $request)
    {
        $request->validated();

        $file = $request->file('import_file');

        try {
            $validData = $this->importDataService->importIndustryDataCheck($file);
        } catch (ValidationException $e) {
            Toastr::addError(nl2br($e->getMessage()));
            return redirect()->back();
        }

        try {
            DB::transaction(function () use ($validData) {
                foreach ($validData as $data) {
                    $this->industryService->addIndustry($data);
                }
            });
            Toastr::addSuccess('Impor data industri berhasil!');
        } catch (\Exception $e) {
            Toastr::addError('Impor data industri gagal!');
        }
        return redirect()->back();
    }

    /**
     * Download the industry import template file.
     */
    public function downloadTemplateFile()
    {
        return $this->downloadService->templateImportDataDownload('template_import_industry.xlsx');
    }

    /**
     * Export industry data to an Excel file.
     */
    public function export(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'NAMA');
        $sheet->setCellValue('C1', 'ALAMAT');
        $sheet->setCellValue('D1', 'EMAIL');
        $sheet->setCellValue('E1', 'NOMOR TELEPON');
        $sheet->setCellValue('F1', 'NAMA PIMPINAN');

        $current_batch = $this->batchService->getBatchByStatus('active');
        $batch_id = $current_batch != null ? $current_batch->id : '';

        $data = $request->data_type == 'Semua'
            ? $this->industryService->getPartnerIndustryList()
            : $this->industryService->getActivePartnerIndustryList($batch_id);

        $row = 2;
        $num = 1;
        foreach ($data as $dt) {
            $sheet->setCellValue('A' . $row, $num);
            $sheet->setCellValue('B' . $row, $dt->name);
            $sheet->setCellValue('C' . $row, $dt->address);
            $sheet->setCellValue('D' . $row, $dt->email);
            $sheet->setCellValue('E' . $row, $dt->phone_num);
            $sheet->setCellValue('F' . $row, $dt->leader_name);
            $row++;
            $num++;
        }

        $filename = "data_industry_export.xlsx";

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
