<?php

namespace App\Http\Controllers\Advisor;

use Illuminate\Http\Request;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use App\Services\AdvisorService;
use App\Services\BatchService;

class IndustryAdvisorController extends Controller
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
        $advisor_id = '2';

        // batch data
        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $request->batch ?? ($currentBatch->id ?? '');
        $batchData = $this->batchService->getAllBatch('');

        $filters=[
            'batch_id'=> $batch_id,
            'search'=> $request->searchKeyword ?? '',
        ];

        $data = $this->internshipService->getIndustryByAdvisor($filters, $advisor_id);

        return view('pages.advisor.industry', [
            'data' => $data,            
            'batchData' => $batchData,
            'filters' => $filters,
        ]);
    }
}
