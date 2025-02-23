<?php

namespace App\Http\Controllers\Advisor;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\AdvisorService;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class InternAdvisorController extends Controller
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
        // $user_id = Auth::user()->id;
        // $advisor_id = $this->advisorService->getAdvisorIdByUserId($user_id);
        $advisor_id = session('user_bio')->id;

        // batch data
        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $request->batch ?? ($currentBatch->id ?? '');
        $batchData = $this->batchService->getAllBatch('');

        $filters=[
            'batch_id'=> $batch_id,
            'search'=> $request->searchKeyword ?? '',
        ];

        $data = $this->internshipService->getInternByAdvisor($filters, $advisor_id);

        return view('pages.advisor.intern', [
            'data' => $data,            
            'batchData' => $batchData,
            'filters' => $filters,
            'pages' => 'intern',
        ]);
    }
}
