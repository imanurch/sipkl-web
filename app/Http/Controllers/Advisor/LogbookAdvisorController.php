<?php

namespace App\Http\Controllers\Advisor;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\BatchService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\AdvisorService;
use App\Services\LogbookService;
use App\Services\InternshipService;
use App\Services\MonitoringService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\MonitoringDocumentService;

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
        // $user_id = Auth::user()->id;
        // $advisor_id = $this->advisorService->getAdvisorIdByUserId($user_id);
        $advisor_id = session('user_bio')->id;

        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $currentBatch->id;

        $batchData = $this->batchService->getAllBatch('');

        // filter
        $filters = [
            'batch_id' => $request->batch ?? $batch_id,
            'search' => $request->searchKeyword ?? '',
            // 'type' => $request->type ?? '',
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
        // dd($data);
        // $internshipListData = $this->internshipService->getInternshipListByAdvisor($advisor_id, $batch_id);



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

    // public function detailLogbook($studentId, $internshipId)
    // {
    //     $logbookData = $this->logbookService->getLogbookByStudentAndInternshipId($studentId, $internshipId);
    //     // dd($logbookData);

    //     return view('pages.advisor.logbook_detail', [
    //         'logbookData' => $logbookData,
    //         // 'filters' => $filters,
    //         // 'internshipListData' => $internshipListData,
    //     ]);
    // }

    // public function downloadFile($type, $filename)
    // {
    //     $formattedString = Str::slug($type, '_');
    //     $path = storage_path('app/monitoring_documents/' . $formattedString . '/' . $filename);

    //     if (file_exists($path)) {
    //         return response()->download($path);
    //     } else {
    //         return response()->json(['message' => 'File tidak ditemukan'], 404);
    //     }
    // }

    // public function store(Request $request)
    // {
    //     // dd($request->all());
    //     $data = $request->except(['_token']);
    //     // dd($request->all());
    //     $validatedData = $request->validate([
    //         'internship_id' => 'required',
    //         'type' => 'required',
    //         'date' => 'required',
    //         'note' => 'nullable|string',
    //     ]);

    //     $newMonitoring = $this->monitoringService->addMonitoring($validatedData);

    //     return back();
    // }



    // public function destroy($id)
    // {
    //     $this->monitoringService->deleteMonitoring($id);
    //     return back();
    // }
}
