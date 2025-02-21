<?php

namespace App\Http\Controllers\Advisor;

use Log;
use Illuminate\Http\Request;
use App\Services\AdminService;
use App\Services\BatchService;
use App\Services\LogbookService;
use App\Services\AssessmentService;
use App\Http\Controllers\Controller;
use App\Services\AdvisorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\InternDocumentService;

class AssessmentAdvisorController extends Controller
{
    protected $assessmentService,
        $batchService,
        $internDocumentService,
        $logbookService,
        $advisorService;

    // Constructor Injection
    public function __construct(
        AssessmentService $assessmentService,
        BatchService $batchService,
        InternDocumentService $internDocumentService,
        LogbookService $logbookService,
        AdvisorService $advisorService
    ) {
        $this->assessmentService = $assessmentService;
        $this->batchService = $batchService;
        $this->internDocumentService = $internDocumentService;
        $this->logbookService = $logbookService;
        $this->advisorService = $advisorService;
    }

    public function index(Request $request)
    {
        // $user_id = Auth::user()->id;
        // $advisor_id = $this->advisorService->getAdvisorIdByUserId($user_id);
        $advisor_id = session('user_bio')->id;

        // batch data
        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $request->batch ?? ($currentBatch->id ?? '');

        // // table filters
        $batchData = $this->batchService->getAllBatch('');
        $filters = [
            'search' => $request->searchKeyword ?? '',
            'batch_id' => $batch_id,
        ];

        // card
        $countAssessed = 0;
        $countNotAssessed = 0;

        // table data
        $data = $this->assessmentService->getAssessmentByAdvisor($advisor_id, $filters);

        foreach ($data as $dt) {
            $final_report = $this->internDocumentService->getInternDocumentByStudentId($dt->student->id, 'laporan akhir');
            $dt->final_report = $final_report != null ? $final_report->url : '';

            $isCompleteLogbook = $this->logbookService->checkIsCompleteLogbookByInternshipAndStudentId($dt->internship_id, $dt->student_id);
            $dt->isCompleteLogbook = $isCompleteLogbook == true ? 'Lengkap' : 'Tidak Lengkap';
            $isCompleteFinalReport = $this->internDocumentService->checkIsCompleteFinalReportByInternshipAndStudentId($dt->internship_id, $dt->student_id);
            $dt->isCompleteFinalReport = $isCompleteFinalReport == true ? 'Lengkap' : 'Tidak Lengkap';


            if ($dt->advisor_score != null) {
                $countAssessed += 1;
            } else {
                $countNotAssessed += 1;
            }
            // // dd($isCompleteFinalReport);
            // if ($isCompleteLogbook == true) {
            //     $isCompleteFinalReport = $this->internDocumentService->checkIsCompleteFinalReportByInternshipAndStudentId($dt->internship_id, $dt->student_id);

            //     if ($isCompleteFinalReport == true) {
            //         $dt->isCompleteOutput = 'Lengkap';
            //     } else {
            //         $dt->isCompleteOutput = 'Tidak Lengkap';
            //     }
            // } else {
            //     $dt->isCompleteOutput = 'Tidak Lengkap';
            // }
        }

        return view('pages.advisor.assessment', [
            'data' => $data,
            'batchData' => $batchData,
            'filters' => $filters,
            'countAssessed' => $countAssessed,
            'countNotAssessed' => $countNotAssessed,
        ]);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        // dd($id);
        $data = $request->except(['_token', '_method']);
        // dd($request->all());
        // dd($data);
        $validatedData = $request->validate([
            'advisor_score' => 'required|numeric',
        ]);
        // dd($validatedData);

        $this->assessmentService->updateScoreAssessment($id, $validatedData);
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
