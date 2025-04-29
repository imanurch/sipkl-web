<?php

namespace App\Http\Controllers\Advisor;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\AdvisorService;
use App\Services\LogbookService;
use App\Services\DownloadService;
use Illuminate\Support\Facades\DB;
use App\Services\AssessmentService;
use App\Http\Controllers\Controller;
use App\Services\InternDocumentService;
use App\Services\TestAssessmentService;
use Flasher\Toastr\Laravel\Facade\Toastr;
use App\Services\AssessmentByAdvisorService;
use App\Services\TechnicalAssessmentService;
use App\Http\Requests\UpdateAssessmentRequest;
use App\Services\FinalReportAssessmentService;
use App\Services\NonTechnicalAssessmentService;
use App\Services\CalculateAssessmentScoreService;

class AssessmentAdvisorController extends Controller
{
    protected
        $assessmentService,
        $assessmentByAdvisorService,
        $batchService,
        $internDocumentService,
        $logbookService,
        $advisorService,
        $technicalAssessmentService,
        $nonTechnicalAssessmentService,
        $finalReportAssessmentService,
        $testAssessmentService,
        $calculateAssessmentScoreService,
        $downloadService;

    // Constructor Injection
    public function __construct(
        AssessmentService $assessmentService,
        AssessmentByAdvisorService $assessmentByAdvisorService,
        BatchService $batchService,
        InternDocumentService $internDocumentService,
        LogbookService $logbookService,
        AdvisorService $advisorService,
        TechnicalAssessmentService $technicalAssessmentService,
        NonTechnicalAssessmentService $nonTechnicalAssessmentService,
        FinalReportAssessmentService $finalReportAssessmentService,
        TestAssessmentService $testAssessmentService,
        CalculateAssessmentScoreService $calculateAssessmentScoreService,
        DownloadService $downloadService
    ) {
        $this->assessmentService = $assessmentService;
        $this->assessmentByAdvisorService = $assessmentByAdvisorService;
        $this->batchService = $batchService;
        $this->internDocumentService = $internDocumentService;
        $this->logbookService = $logbookService;
        $this->advisorService = $advisorService;
        $this->technicalAssessmentService = $technicalAssessmentService;
        $this->nonTechnicalAssessmentService = $nonTechnicalAssessmentService;
        $this->finalReportAssessmentService = $finalReportAssessmentService;
        $this->testAssessmentService = $testAssessmentService;
        $this->calculateAssessmentScoreService = $calculateAssessmentScoreService;
        $this->downloadService = $downloadService;
    }

    public function index(Request $request)
    {
        $advisor_id = session('user_bio')->id;

        // batch data
        $batch_id = $this->batchService->getRelevantBatch($request->batch);
        
        // table filters
        $batchData = $this->batchService->getAllBatch('');
        $filters = [
            'search' => $request->searchKeyword ?? '',
            'batch_id' => $batch_id,
        ];

        // card
        $countNotAssessed = $this->assessmentByAdvisorService->getNotAssessedCountByAdvisor($advisor_id);
        $countPass = $this->assessmentByAdvisorService->getAssessedCountByAdvisor($advisor_id, 'pass');
        $countNotPass = $this->assessmentByAdvisorService->getAssessedCountByAdvisor($advisor_id, 'notPass');

        // table data
        $data = $this->assessmentByAdvisorService->getAssessmentByAdvisor($advisor_id, $filters);

        foreach ($data as $dt) {
            $final_report = $this->internDocumentService->getInternDocumentByStudentId($dt->student->id, 'laporan akhir');
            $dt->final_report = $final_report != null ? $final_report->url : '';

            $isCompleteLogbook = $this->logbookService->checkIsCompleteLogbookByInternshipAndStudentId($dt->internship_id, $dt->student_id);
            $dt->isCompleteLogbook = $isCompleteLogbook == true ? 'Lengkap' : 'Tidak Lengkap';
            $isCompleteFinalReport = $this->internDocumentService->checkIsCompleteFinalReportByInternshipAndStudentId($dt->internship_id, $dt->student_id);
            $dt->isCompleteFinalReport = $isCompleteFinalReport == true ? 'Lengkap' : 'Tidak Lengkap';

            // final score internship
            $this->calculateAssessmentScoreService->calculateInternshipScore($dt);
        }

        return view('pages.advisor.assessment', [
            'data' => $data,
            'batchData' => $batchData,
            'filters' => $filters,
            'countNotAssessed' => $countNotAssessed,
            'countPass' => $countPass,
            'countNotPass' => $countNotPass,
            'pages' => 'assessment',
        ]);
    }

    public function update(UpdateAssessmentRequest $request, $id)
    {
        $request->validated();
        try {
            DB::transaction(function () use ($request, $id) {
                // technical
                $this->technicalAssessmentService->deleteTechnicalAssessment($id);
                if ($request->technical_aspect != null) {
                    foreach ($request->technical_aspect as $index => $aspect) {
                        $this->technicalAssessmentService->addTechnicalAssessment([
                            'assessment_id' => $id,
                            'aspect' => $aspect,
                            'score' => $request->technical_score[$index] ?? null,
                        ]);
                    }
                }

                // non technical
                $this->nonTechnicalAssessmentService->updateOrCreate($id, $request);

                // final_report
                $this->finalReportAssessmentService->updateOrCreate($id, $request);

                // test
                if ($request->final_test != null) {
                    $this->testAssessmentService->updateOrCreate([
                        'assessment_id' => $id,
                        'score' => $request->final_test,
                    ]);
                }
            });

            Toastr::addSuccess('Penilaian berhasil ditambahkan!');
        } catch (\Exception $e) {
            Toastr::addError('Penilaian gagal ditambahkan!');
        }
        return redirect()->back();
    }

    public function downloadLaporanAkhir($filename)
    {
        $this->downloadService->internDocumentDownload('laporan akhir', $filename);
    }
}
