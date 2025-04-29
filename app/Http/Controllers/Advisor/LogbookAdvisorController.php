<?php

namespace App\Http\Controllers\Advisor;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\AdvisorService;
use App\Services\LogbookService;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;

class LogbookAdvisorController extends Controller
{
    protected
        $batchService,
        $internshipService,
        $logbookService,
        $advisorService;

    // Constructor Injection
    public function __construct(
        BatchService $batchService,
        InternshipService $internshipService,
        LogbookService $logbookService,
        AdvisorService $advisorService
    ) {
        $this->batchService = $batchService;
        $this->internshipService = $internshipService;
        $this->logbookService = $logbookService;
        $this->advisorService = $advisorService;
    }

    public function index(Request $request)
    {
        $advisor_id = session('user_bio')->id;

        $batchData = $this->batchService->getAllBatch('');
        $batch_id = $this->batchService->getRelevantBatch($request->batch);

        // filter
        $filters = [
            'batch_id' => $request->batch ?? $batch_id,
            'search' => $request->searchKeyword ?? '',
        ];

        $data = $this->internshipService->getInternByAdvisor($filters, $advisor_id);
        foreach ($data as $dt) {
            foreach ($dt->groupMember as $member) {
                if ($member->group->internship) {
                    $isCompleteLogbook = $this->logbookService->checkIsCompleteLogbookByInternshipAndStudentId($member->group->internship->id, $dt->id);
                    $dt->status = $isCompleteLogbook == true ? 'Lengkap' : 'Tidak Lengkap';
                }
            }
        }

        $unconfirmedCount = $this->logbookService->countLogbookByAdvisorStatus('unconfirmed', $batch_id, $advisor_id);
        $acceptedCount = $this->logbookService->countLogbookByAdvisorStatus('accepted', $batch_id, $advisor_id);
        $revisedCount = $this->logbookService->countLogbookByAdvisorStatus('revised', $batch_id, $advisor_id);
        
        return view('pages.advisor.logbook', [
            'data' => $data,
            'unconfirmedCount' => $unconfirmedCount,
            'acceptedCount' => $acceptedCount,
            'revisedCount' => $revisedCount,
            'filters' => $filters,
            'batchData' => $batchData,
            'pages' => 'logbook',
        ]);
    }
}
