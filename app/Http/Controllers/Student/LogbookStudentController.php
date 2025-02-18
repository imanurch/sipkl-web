<?php

namespace App\Http\Controllers\Student;

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

class LogbookStudentController extends Controller
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

    public function index()
    {
        $currentBatch = $this->batchService->getBatchByStatus('active');

        $student_id = '8';
        // $studentData = $this->studentService->getStudentById($studentId);
        $data = $this->logbookService->getLogbookByStudentIdAndBatch($currentBatch->id, $student_id);
        // $internshipData = $this->internshipService->getInternshipByInternshipId($internshipId);
        // $studentData->industry = $internshipData->industry->name;
        // dd($data);

        return view('pages.student.logbook', [
            // 'studentData' => $studentData,
            'data' => $data,
            // 'internshipListData' => $internshipListData,
        ]);
    }

    public function update(Request $request, $id)
    {
        // dd($logbookId, $status, $request->all());
        $validatedData = $request->validate([
            'activities' => 'required',
        ]);

        $this->logbookService->updateLogbook($id, $validatedData);
        return back();
    }
}
