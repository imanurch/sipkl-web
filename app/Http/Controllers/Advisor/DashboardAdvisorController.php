<?php

namespace App\Http\Controllers\Advisor;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\AdvisorService;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;

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
        $advisor_id = session('user_bio')->id;

        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $currentBatch != null ? $currentBatch->id : '';

        $data = $this->advisorService->getAdvisorById($advisor_id);

        $mentee = $this->internshipService->getInternByAdvisorCount($batch_id, $advisor_id);
        $industry = $this->internshipService->getIndustryByAdvisorCount($batch_id, $advisor_id);

        return view('pages.advisor.dashboard', [
            'data' => $data,
            'mentee' => $mentee,
            'industry' => $industry,
            'pages' => 'dashboard',
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
