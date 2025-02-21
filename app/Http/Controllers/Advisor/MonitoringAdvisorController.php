<?php

namespace App\Http\Controllers\Advisor;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\BatchService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\AdvisorService;
use App\Services\InternshipService;
use App\Services\MonitoringService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\MonitoringDocumentService;

class MonitoringAdvisorController extends Controller
{
    protected $monitoringService,
        $batchService,
        $internshipService,
        $monitoringDocumentService,
        $advisorService;

    // Constructor Injection
    public function __construct(
        MonitoringService $monitoringService,
        BatchService $batchService,
        InternshipService $internshipService,
        MonitoringDocumentService $monitoringDocumentService,
        AdvisorService $advisorService
    ) {
        $this->monitoringService = $monitoringService;
        $this->batchService = $batchService;
        $this->internshipService = $internshipService;
        $this->monitoringDocumentService = $monitoringDocumentService;
        $this->advisorService = $advisorService;
    }

    public function index(Request $request)
    {
        // $user_id = Auth::user()->id;
        // $advisor_id = $this->advisorService->getAdvisorIdByUserId($user_id);
        $advisor_id = session('user_bio')->id;
        
        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $currentBatch->id;

        // filter
        $filters = [
            'search' => $request->searchKeyword ?? '',
            'type' => $request->type ?? '',
        ];

        $data = $this->monitoringService->getMonitoringByAdvisorIdAndBatch($advisor_id, $batch_id, $filters);
        // dd($data);
        $internshipListData = $this->internshipService->getInternshipListByAdvisor($advisor_id, $batch_id);


        return view('pages.advisor.monitoring', [
            'data' => $data,
            'filters' => $filters,
            'internshipListData' => $internshipListData,
        ]);
    }

    public function downloadFile($type, $filename)
    {
        $formattedString = Str::slug($type, '_');
        $path = storage_path('app/monitoring_documents/' . $formattedString . '/' . $filename);

        if (file_exists($path)) {
            return response()->download($path);
        } else {
            return response()->json(['message' => 'File tidak ditemukan'], 404);
        }
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $data = $request->except(['_token']);
        // dd($request->all());
        $validatedData = $request->validate([
            'internship_id' => 'required',
            'type' => 'required',
            'date' => 'required',
            'note' => 'nullable|string',
        ]);

        $newMonitoring = $this->monitoringService->addMonitoring($validatedData);

        $dataDoc = [
            'title' => 'Contoh Dokumen PDF',
            'date'  => date('d-m-Y'),
        ];
        $pdf = Pdf::loadView('document_templates/surat_pengantar_template', $dataDoc);

        if ($newMonitoring->type == 'Pelepasan') {
            // generate surat pengantar
            $filename = 'surat_pengantar' . time() . '.pdf';
            $path = storage_path('app/monitoring_documents/surat_pengantar/' . $filename);
            $pdf->save($path);

            $monitoringDocumentData = [
                'monitoring_id' => $newMonitoring->id,
                'type' => 'surat pengantar',
                'url' => $filename,
            ];

            $this->monitoringDocumentService->addMonitoringDocument($monitoringDocumentData);

            // generate doc lain
        } elseif ($newMonitoring->type == 'Kunjungan') {
            // generate surat pengantar
            $filename = 'surat_tugas' . time() . '.pdf';
            $path = storage_path('app/monitoring_documents/surat_tugas/' . $filename);
            $pdf->save($path);

            $monitoringDocumentData = [
                'monitoring_id' => $newMonitoring->id,
                'type' => 'surat tugas',
                'url' => $filename,
            ];

            $this->monitoringDocumentService->addMonitoringDocument($monitoringDocumentData);

            // generate doc lain
        } elseif ($newMonitoring->type == 'Penarikan') {
            // generate surat pengantar
            $filename = 'surat_penarikan' . time() . '.pdf';
            $path = storage_path('app/monitoring_documents/surat_penarikan/' . $filename);
            $pdf->save($path);

            $monitoringDocumentData = [
                'monitoring_id' => $newMonitoring->id,
                'type' => 'surat penarikan',
                'url' => $filename,
            ];

            $this->monitoringDocumentService->addMonitoringDocument($monitoringDocumentData);

            // generate doc lain
        }

        return back();
    }

    public function update(Request $request, $id)
    {
        $data = $request->except(['_token', '_method']);
        $validatedData = $request->validate([
            'internship_id' => 'required',
            'type' => 'required',
            'date' => 'required',
            'note' => 'nullable|string',
        ]);

        $this->monitoringService->updateMonitoring($id, $validatedData);
        return back();
    }

    public function destroy($id)
    {
        $this->monitoringService->deleteMonitoring($id);
        return back();
    }
}
