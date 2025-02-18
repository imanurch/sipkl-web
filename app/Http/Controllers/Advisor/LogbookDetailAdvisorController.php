<?php

namespace App\Http\Controllers\Advisor;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\BatchService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\LogbookService;
use App\Services\InternshipService;
use App\Services\MonitoringService;
use App\Http\Controllers\Controller;
use App\Services\MonitoringDocumentService;
use App\Services\StudentService;

class LogbookDetailAdvisorController extends Controller
{
    protected
        $batchService,
        $internshipService,
        $logbookService,
        $studentService;

    // Constructor Injection
    public function __construct(
        BatchService $batchService,
        InternshipService $internshipService,
        LogbookService $logbookService,
        StudentService $studentService
    ) {
        $this->batchService = $batchService;
        $this->internshipService = $internshipService;
        $this->logbookService = $logbookService;
        $this->studentService = $studentService;
    }

    public function index($studentId, $internshipId)
    {
        $studentData = $this->studentService->getStudentById($studentId);
        $logbookData = $this->logbookService->getLogbookByStudentAndInternshipId($studentId, $internshipId);
        $internshipData = $this->internshipService->getInternshipByInternshipId($internshipId);
        $studentData->industry = $internshipData->industry->name;
        // dd($studentData);

        return view('pages.advisor.logbook_detail', [
            'studentData' => $studentData,
            'logbookData' => $logbookData,
            // 'internshipListData' => $internshipListData,
        ]);
    }

    public function statusConfirm(Request $request, $logbookId, $status)
    {
        // dd($logbookId, $status, $request->all());
        $data = [
            'status' => $status == 'accept' ? '1' : '2',
            'feedback' => $request->feedback,
        ];

        $this->logbookService->updateLogbook($logbookId, $data);
        return back();
    }
}
