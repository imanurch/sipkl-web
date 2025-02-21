<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\AdvisorService;
use App\Services\StudentService;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
        $user_id = Auth::user()->id;
        // $student_id = $this->studentService->getStudentIdByUserId($user_id);
        $student_id = session('user_bio')->id;
        
        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $currentBatch != null ? $currentBatch->id : '';

           // $studentData = $this->studentService->getStudentById($student_id);
        $internshipData = $this->internshipService->getInternshipByStudentId($batch_id, $student_id);
        // dd($internshipData);
        return view('pages.student.dashboard', [
            // 'studentData' => $studentData,
            'internshipData' => $internshipData,
        ]);
    }
}
