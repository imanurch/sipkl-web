<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\StudentService;
use App\Services\AssessmentService;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\InternDocumentService;

class FinalReportStudentController extends Controller
{
    protected
        $internDocumentService,
        $assessmentService,
        $batchService,
        $studentService,
        $internshipService;

    // Constructor Injection
    public function __construct(
        InternDocumentService $internDocumentService,
        AssessmentService $assessmentService,
        BatchService $batchService,
        StudentService $studentService,
        InternshipService $internshipService
    ) {
        $this->internDocumentService = $internDocumentService;
        $this->assessmentService = $assessmentService;
        $this->batchService = $batchService;
        $this->studentService = $studentService;
        $this->internshipService = $internshipService;
    }

    public function index(Request $request)
    {
        $user_id = Auth::user()->id;
        // $student_id = $this->studentService->getStudentIdByUserId($user_id);
        $student_id = session('user_bio')->id;
        
        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $currentBatch != null ? $currentBatch->id : '';
        $isIntern = $this->internshipService->getInternshipByStudentId($batch_id,$student_id) != null ? true : false;

        $data = $this->internDocumentService->getInternDocumentByStudentId($student_id, 'laporan akhir');
        // dd($data);
        if ($data != null) {
            $assessment = $this->assessmentService->getAssessmentByStudentIdAndInternshipId($student_id, $data->internship_id);
            $data->isAssessed = ($assessment->industry_score != null && $assessment->advisor_score != null && $assessment->final_test_score != null ? true : false);
        }
        // dd($data);
        return view('pages.student.final_report', [
            'data' => $data,
            'isIntern' => $isIntern,
        ]);
    }

    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        // $student_id = $this->studentService->getStudentIdByUserId($user_id);
        $student_id = session('user_bio')->id;
        $batch_id = $this->batchService->getBatchByStatus('active')->id;
        $internship_id = $this->internshipService->getInternshipByStudentId($batch_id, $student_id)->id;
        // dd($internship_id);

        $validatedData = $request->validate([
            // 'student_id' => 'required',
            // 'internship_id' => 'required',
            'laporan_akhir' => 'required|mimes:pdf',
        ]);
        // dd($validatedData);
        $path_file_balasan = $validatedData['laporan_akhir']->store('intern_documents/laporan_akhir');
        $filename = basename($path_file_balasan);

        $data = [
            'student_id' => $student_id,
            'internship_id' => $internship_id,
            'type' => 'laporan akhir',
            'url' => $filename,
        ];
        // dd($data);

        $this->internDocumentService->addInternDocument($data);
        return back();
    }

    public function downloadLaporanAkhir($filename)
    {
        $path = storage_path('app/intern_documents/laporan_akhir/' . $filename);

        if (file_exists($path)) {
            return response()->download($path);
        } else {
            return response()->json(['message' => 'File tidak ditemukan'], 404);
        }
    }
}
