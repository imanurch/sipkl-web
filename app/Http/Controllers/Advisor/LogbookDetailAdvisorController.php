<?php

namespace App\Http\Controllers\Advisor;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\LogbookService;
use App\Services\StudentService;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use Flasher\Toastr\Laravel\Facade\Toastr;

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

    /**
     * Display the detail logbook page with confirmation access.
     */
    public function index($studentId, $internshipId)
    {
        $studentData = $this->studentService->getStudentById($studentId);
        $logbookData = $this->logbookService->getLogbookByStudentAndInternshipId($studentId, $internshipId);
        $internshipData = $this->internshipService->getInternshipByInternshipId($internshipId);
        $studentData->industry = $internshipData->industry->name;

        return view('pages.advisor.logbook_detail', [
            'studentData' => $studentData,
            'logbookData' => $logbookData,
            'pages' => 'logbook',
        ]);
    }

    /**
     * Handle logbook status confirmation (accept or revise) from advisor with optional feedback.
     */
    public function statusConfirm(Request $request, $logbookId, $status)
    {
        try {
            $data = [
                'status' => $status == 'accept' ? '1' : '2',
                'feedback' => $request->feedback,
            ];

            $this->logbookService->updateLogbook($logbookId, $data);
            Toastr::addSuccess('Logbook berhasil dikonfirmasi!');
        } catch (\Exception $e) {
            Toastr::addError('Logbook gagal dikonfirmasi!');
        }
        return redirect()->back();
    }
}
