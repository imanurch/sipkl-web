<?php

namespace App\Http\Controllers\Admin;

use Log;
use Illuminate\Http\Request;
use App\Services\AdminService;
use App\Services\AssessmentService;
use App\Http\Controllers\Controller;
use App\Services\BatchService;
use App\Services\InternDocumentService;
use App\Services\LogbookService;
use Illuminate\Support\Facades\Hash;

class AssessmentAdminController extends Controller
{
    protected $assessmentService,
        $batchService,
        $internDocumentService,
        $logbookService;

    // Constructor Injection
    public function __construct(
        AssessmentService $assessmentService,
        BatchService $batchService,
        InternDocumentService $internDocumentService,
        LogbookService $logbookService
    ) {
        $this->assessmentService = $assessmentService;
        $this->batchService = $batchService;
        $this->internDocumentService = $internDocumentService;
        $this->logbookService = $logbookService;
    }

    public function index(Request $request)
    {
        // batch data
        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $request->batch ?? ($currentBatch->id ?? '');

        // table filters
        $batchData = $this->batchService->getAllBatch('');
        $filters = [
            'search' => $request->searchKeyword ?? '',
            'batch_id' => $batch_id,
        ];

        // card
        $countNotAssessed = 0;
        $countPass = 0;
        $countNotPass = 0;

        // table data
        $data = $this->assessmentService->getAssessment($filters);

        foreach ($data as $dt) {
            // hitung nilai akhir internship
            if ($dt->industry_score && $dt->advisor_score && $dt->final_test_score) {
                $dt->internship_score = round((($dt->industry_score) + ($dt->advisor_score) + ($dt->final_test_score)) / 3, 2);

                // cek kelulusan
                if($dt->internship_score >= 75){
                    $dt->internship_status = 'Lulus';
                    $countPass +=1;
                } else{
                    $dt->internship_status = 'Tidak Lulus';
                    $countNotPass +=1;
                }
            } else{
                $countNotAssessed +=1;
            }

            // cek final report
            $isCompleteFinalReport = $this->internDocumentService->checkIsCompleteFinalReportByInternshipAndStudentId($dt->internship_id, $dt->student_id);
            // dd($isCompleteFinalReport);
            if ($isCompleteFinalReport == true) {
                // cek logbook
                $isCompleteLogbook = $this->logbookService->checkIsCompleteLogbookByInternshipAndStudentId($dt->internship_id, $dt->student_id);
                if ($isCompleteLogbook == true) {
                    $dt->isCompleteOutput = 'Lengkap';
                } else {
                    $dt->isCompleteOutput = 'Tidak Lengkap';
                }
            } else {
                $dt->isCompleteOutput = 'Tidak Lengkap';
            }
            // dd($dt->isCompleteOutput);
        }

        return view('pages.admin.assessment', [
            'data' => $data,
            'batchData' => $batchData,
            'filters' => $filters,
            'countNotAssessed' => $countNotAssessed,
            'countPass' => $countPass,
            'countNotPass' => $countNotPass,
            'pages' => 'assessment',
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
            'industry_score' => 'nullable|numeric',
            'advisor_score' => 'nullable|numeric',
            'final_test_score' => 'nullable|numeric',
        ]);
        // dd($validatedData);
        
        $this->assessmentService->updateScoreAssessment($id, $validatedData);
        return back();
    }
}
