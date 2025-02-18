<?php

namespace App\Http\Controllers\Advisor;

use Illuminate\Http\Request;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use App\Services\AdvisorService;
use App\Services\BatchService;

class DashboardAdvisorController extends Controller
{
    protected $internshipService, $advisorService, $batchService;

    // Constructor Injection
    public function __construct(InternshipService $internshipService, AdvisorService $advisorService, BatchService $batchService)
    {
        $this->internshipService = $internshipService;
        $this->advisorService = $advisorService;
        $this->batchService = $batchService;
    }

    public function index(Request $request)
    {
        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $currentBatch->id;
        $advisor_id = '2';

        $mentee = $this->internshipService->getInternByAdvisorCount($batch_id, $advisor_id);
        $industry = $this->internshipService->getIndustryByAdvisorCount($batch_id, $advisor_id);

        $data = $this->advisorService->getAdvisorById($advisor_id);
        $surat_tugas = $this->advisorService->getAdvisorDocumentByAdvisorIdAndBatchId($advisor_id, $batch_id);
        $data->surat_tugas = $surat_tugas;
        // dd($surat_tugas);

        return view('pages.advisor.dashboard', [
            'data' => $data,
            // 'surat_tugas' => $surat_tugas,
            'mentee' => $mentee,
            'industry' => $industry,
        ]);
    }

    public function downloadSuratTugas($filename)
    {
        $path = storage_path('app/registration_document/surat_pengantar/' . $filename);

        if (file_exists($path)) {
            return response()->download($path);
        } else {
            return response()->json(['message' => 'File tidak ditemukan'], 404);
        }
    }
}
