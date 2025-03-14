<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use App\Services\AdvisorService;
use App\Services\BatchService;
use App\Services\IndustryService;
use App\Services\RegistrationService;

class DocumentController extends Controller
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
        $batchData = $this->batchService->getAllBatch('');

        return view('pages.admin.document', [
            'batchData' => $batchData,
            'pages' => 'document',
        ]);
    }

    public function advisorDocumentSearch(Request $request){
        $batchData = $this->batchService->getAllBatch('');

        $validatedData = $request->validate([
            'advisor_nip'=>'required',
            'batch'=>'required'
        ]);
        $advisorData = $this->advisorService->getAdvisorByNIP($request->advisor_nip, $request->batch);
        return view('pages.admin.document', [
            'batchData' => $batchData,
            'advisorData' => $advisorData,
            'pages' => 'document',
        ]);
    }
}
