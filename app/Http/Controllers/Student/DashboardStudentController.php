<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use App\Services\AdvisorService;
use App\Services\BatchService;
use App\Services\StudentService;

class DashboardStudentController extends Controller
{
    protected $studentService, $internshipService, $advisorService, $batchService;

    // Constructor Injection
    public function __construct(StudentService $studentService, InternshipService $internshipService, AdvisorService $advisorService, BatchService $batchService)
    {
        $this->studentService = $studentService;
        $this->internshipService = $internshipService;
        $this->advisorService = $advisorService;
        $this->batchService = $batchService;
    }

    public function index(Request $request)
    {
        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $currentBatch->id;
        $student_id = '5';

        // $mentee = $this->internshipService->getInternByAdvisorCount($batch_id, $advisor_id);
        // $industry = $this->internshipService->getIndustryByAdvisorCount($batch_id, $advisor_id);

        $studentData = $this->studentService->getStudentById($student_id);
        $internshipData = $this->internshipService->getInternshipByStudentId($batch_id, $student_id);
        // dd($internshipData);
        return view('pages.student.dashboard', [
            'studentData' => $studentData,
            'internshipData' => $internshipData,
            // 'industry' => $industry,
        ]);
    }
}
