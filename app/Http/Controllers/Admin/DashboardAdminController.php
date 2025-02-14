<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use App\Services\AdvisorService;
use App\Services\BatchService;
use App\Services\IndustryService;
use App\Services\RegistrationService;

class DashboardAdminController extends Controller
{
    protected $internshipService, $advisorService, $industryService, $registrationService, $batchService;

    // Constructor Injection
    public function __construct(InternshipService $internshipService, AdvisorService $advisorService, IndustryService $industryService, RegistrationService $registrationService, BatchService $batchService)
    {
        $this->internshipService = $internshipService;
        $this->advisorService = $advisorService;
        $this->industryService = $industryService;
        $this->registrationService = $registrationService;
        $this->batchService = $batchService;
    }

    public function index(Request $request)
    {
        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $request->batch ?? ($currentBatch->id ?? '');

        $intern = $this->internshipService->getInternCount($batch_id);
        $advisor = $this->advisorService->getAdvisorByStatusCount($batch_id, 'active');
        $industry = $this->industryService->getIndustryByStatusCount($batch_id, 'active');
        $registration = $this->registrationService->getRegistrationByStatusCount('unconfirmed', $batch_id);

        return view('pages.admin.dashboard', [
            'intern' => $intern,
            'advisor' => $advisor,
            'industry' => $industry,
            'registration' => $registration,
        ]);
    }
}
