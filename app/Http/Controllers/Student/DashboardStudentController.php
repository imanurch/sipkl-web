<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\AdvisorService;
use App\Services\StudentService;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use App\Services\AssessmentService;
use Illuminate\Support\Facades\Auth;

class DashboardStudentController extends Controller
{
    protected
        $studentService,
        $internshipService,
        $advisorService,
        $batchService,
        $assessmentService;

    // Constructor Injection
    public function __construct(
        StudentService $studentService,
        InternshipService $internshipService,
        AdvisorService $advisorService,
        BatchService $batchService,
        AssessmentService $assessmentService
    ) {
        $this->studentService = $studentService;
        $this->internshipService = $internshipService;
        $this->advisorService = $advisorService;
        $this->batchService = $batchService;
        $this->assessmentService = $assessmentService;
    }

    public function index(Request $request)
    {
        $user_id = Auth::user()->id;
        // $student_id = $this->studentService->getStudentIdByUserId($user_id);
        $student_id = session('user_bio')->id;

        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $currentBatch != null ? $currentBatch->id : '';

        // $studentData = $this->studentService->getStudentById($student_id);
        $internshipData = $this->internshipService->getInternshipByStudentId($batch_id, $student_id);
        if ($internshipData != null) {
            $assessment = $this->assessmentService->getAssessmentByStudentIdAndInternshipId($student_id, $internshipData->id);
            $isAssessed = ($assessment->advisor_score != null && $assessment->industry_score != null && $assessment->final_test_score != null ? 'true' : false);
            $internshipData->status = $isAssessed == true ? 'Selesai' : 'Sedang Berjalan';
        }
        // dd($internshipData);
        return view('pages.student.dashboard', [
            // 'studentData' => $studentData,
            'internshipData' => $internshipData,
            'pages' => 'dashboard',
        ]);
    }
}
