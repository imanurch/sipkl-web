<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\AssessmentService;
use App\Http\Controllers\Controller;
use App\Services\InternDocumentService;

class FinalReportStudentController extends Controller
{
    protected $internDocumentService, $assessmentService, $batchService;

    // Constructor Injection
    public function __construct(InternDocumentService $internDocumentService, AssessmentService $assessmentService, BatchService $batchService)
    {
        $this->internDocumentService = $internDocumentService;
        $this->assessmentService = $assessmentService;
        $this->batchService = $batchService;
    }

    public function index(Request $request)
    {
        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $currentBatch->id;
        $student_id = '4';

        $data = $this->internDocumentService->getInternDocumentByStudentId($student_id, 'laporan akhir');
        // dd($data);
        if($data != null){
            $assessment = $this->assessmentService->getAssessmentByStudentIdAndInternshipId($student_id, $data->internship_id);
            $data->isAssessed = ($assessment->industry_score != null && $assessment->advisor_score != null && $assessment->final_test_score != null ? true : false);
        }
        // dd($data);
        return view('pages.student.final_report', [
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // 'student_id' => 'required',
            // 'internship_id' => 'required',
            'laporan_akhir' => 'required|mimes:pdf',
        ]);
        // dd($validatedData);
        $path_file_balasan = $validatedData['laporan_akhir']->store('intern_documents/laporan_akhir');
        $filename = basename($path_file_balasan);

        $data= [
            'student_id' => '4',
            'internship_id' => '15',
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
