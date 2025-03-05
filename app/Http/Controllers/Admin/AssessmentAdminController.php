<?php

namespace App\Http\Controllers\Admin;

use Log;
use Illuminate\Http\Request;
use App\Services\AdminService;
use App\Services\BatchService;
use App\Services\LogbookService;
use App\Services\AssessmentService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Services\InternDocumentService;
use Flasher\Toastr\Laravel\Facade\Toastr;

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
        $countNotAssessed = $this->assessmentService->getNotAssessedCount();
        $countPass = $this->assessmentService->getAssessedCount('pass');
        $countNotPass = $this->assessmentService->getAssessedCount('notPass');

        // table data
        $data = $this->assessmentService->getAssessment($filters);

        foreach ($data as $dt) {
            // hitung nilai akhir internship
            if ($dt->industry_score && $dt->advisor_score && $dt->final_test_score) {
                $dt->internship_score = round((($dt->industry_score) + ($dt->advisor_score) + ($dt->final_test_score)) / 3, 2);

                // cek kelulusan
                if($dt->internship_score >= 75){
                    $dt->internship_status = 'Lulus';
                } else{
                    $dt->internship_status = 'Tidak Lulus';
                }
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
        $data = $request->except(['_token', '_method']);
        try {
            $validatedData = $request->validate([
                'industry_score' => 'nullable|numeric',
                'advisor_score' => 'nullable|numeric',
                'final_test_score' => 'nullable|numeric',
            ]);
            
            $this->assessmentService->updateScoreAssessment($id, $validatedData);
            Toastr::addSuccess('Penilaian berhasil ditambahkan!');
        } catch (\Exception $e) {
            Toastr::addError('Penilaian gagal ditambahkan!');
        }
        return redirect()->back();
    }
}