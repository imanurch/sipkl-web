<?php

namespace App\Http\Controllers\Advisor;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\AdvisorService;
use App\Services\LogbookService;
use Illuminate\Support\Facades\DB;
use App\Services\AssessmentService;
use App\Http\Controllers\Controller;
use App\Services\InternDocumentService;
use App\Services\TestAssessmentService;
use Flasher\Toastr\Laravel\Facade\Toastr;
use App\Services\TechnicalAssessmentService;
use App\Services\FinalReportAssessmentService;
use App\Services\NonTechnicalAssessmentService;

class AssessmentAdvisorController extends Controller
{
    protected $assessmentService,
        $batchService,
        $internDocumentService,
        $logbookService,
        $advisorService,
        $technicalAssessmentService,
        $nonTechnicalAssessmentService,
        $finalReportAssessmentService,
        $testAssessmentService;

    // Constructor Injection
    public function __construct(
        AssessmentService $assessmentService,
        BatchService $batchService,
        InternDocumentService $internDocumentService,
        LogbookService $logbookService,
        AdvisorService $advisorService,
        TechnicalAssessmentService $technicalAssessmentService,
        NonTechnicalAssessmentService $nonTechnicalAssessmentService,
        FinalReportAssessmentService $finalReportAssessmentService,
        TestAssessmentService $testAssessmentService
    ) {
        $this->assessmentService = $assessmentService;
        $this->batchService = $batchService;
        $this->internDocumentService = $internDocumentService;
        $this->logbookService = $logbookService;
        $this->advisorService = $advisorService;
        $this->technicalAssessmentService = $technicalAssessmentService;
        $this->nonTechnicalAssessmentService = $nonTechnicalAssessmentService;
        $this->finalReportAssessmentService = $finalReportAssessmentService;
        $this->testAssessmentService = $testAssessmentService;
    }

    public function index(Request $request)
    {
        $advisor_id = session('user_bio')->id;

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
        $countNotAssessed = $this->assessmentService->getNotAssessedCountByAdvisor($advisor_id);
        $countPass = $this->assessmentService->getAssessedCountByAdvisor($advisor_id, 'pass');
        $countNotPass = $this->assessmentService->getAssessedCountByAdvisor($advisor_id, 'notPass');

        // table data
        $data = $this->assessmentService->getAssessmentByAdvisor($advisor_id, $filters);

        foreach ($data as $dt) {
            $final_report = $this->internDocumentService->getInternDocumentByStudentId($dt->student->id, 'laporan akhir');
            $dt->final_report = $final_report != null ? $final_report->url : '';

            $isCompleteLogbook = $this->logbookService->checkIsCompleteLogbookByInternshipAndStudentId($dt->internship_id, $dt->student_id);
            $dt->isCompleteLogbook = $isCompleteLogbook == true ? 'Lengkap' : 'Tidak Lengkap';
            $isCompleteFinalReport = $this->internDocumentService->checkIsCompleteFinalReportByInternshipAndStudentId($dt->internship_id, $dt->student_id);
            $dt->isCompleteFinalReport = $isCompleteFinalReport == true ? 'Lengkap' : 'Tidak Lengkap';

            $technical_score = 0;
            $non_technical_score = 0;
            $final_report_score = 0;
            $technical_aspect = [];
            $technical_aspect_score = [];

            // technical
            if (count($dt->technical_assessment) > 0) {
                foreach ($dt->technical_assessment as $aspect_score) {
                    $technical_score += $aspect_score->score;
                    $technical_aspect[] = $aspect_score->aspect;
                    $technical_aspect_score[] = $aspect_score->score;
                    $dt->technical_aspect = $technical_aspect;
                    $dt->technical_aspect_score = $technical_aspect_score;
                }
                $technical_score_average = $technical_score / count($dt->technical_assessment);
                $dt->technical_score_average = $technical_score_average;
            }

            // non technical
            if (count($dt->non_technical_assessment) > 0) {
                foreach ($dt->non_technical_assessment as $aspect_score) {
                    $non_technical_score += $aspect_score->score;
                    $aspect = str_replace(' ', '_', $aspect_score->aspect);
                    $dt->$aspect = $aspect_score->score;
                }
                if ($this->nonTechnicalAssessmentService->isNonTechnicalAssessmentComplete($dt->id) == true) {
                    $non_technical_score_average = ($non_technical_score / count($dt->non_technical_assessment));
                    $dt->non_technical_score_average = $non_technical_score_average;
                }
            }

            // final report
            if (count($dt->final_report_assessment) > 0) {
                foreach ($dt->final_report_assessment as $aspect_score) {
                    $final_report_score += $aspect_score->score;
                    $aspect = str_replace(' ', '_', $aspect_score->aspect);
                    $dt->$aspect = $aspect_score->score;
                }
                if ($this->finalReportAssessmentService->isFinalReportAssessmentComplete($dt->id) == true) {
                    $final_report_score_average = $final_report_score / count($dt->final_report_assessment);
                    $dt->final_report_score_average = $final_report_score_average;
                }
            }

            // final score internship
            if (
                count($dt->technical_assessment) > 0 &&
                $this->technicalAssessmentService->isTechnicalAssessmentComplete($dt->id) == true &&
                $this->nonTechnicalAssessmentService->isNonTechnicalAssessmentComplete($dt->id) == true &&
                $this->finalReportAssessmentService->isFinalReportAssessmentComplete($dt->id) == true &&
                $this->testAssessmentService->isTestAssessmentComplete($dt->id) == true
            ) {
                $dt->internship_score = round((($technical_score_average + $non_technical_score_average + $final_report_score_average + $dt->test_assessment->score) / 4), 2);
                // cek kelulusan
                if ($dt->internship_score >= 75) {
                    $dt->internship_status = 'Lulus';
                } else {
                    $dt->internship_status = 'Tidak Lulus';
                }
            }
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

    public function update(Request $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id) {
                $validatedData = $request->validate([
                    'technical_aspect[]' => 'nullable|string',
                    'technical_score[]' => 'nullable|numeric',
                    'dicipline' => 'nullable|numeric',
                    'teamwork' => 'nullable|numeric',
                    'initiative' => 'nullable|numeric',
                    'responsibility' => 'nullable|numeric',
                    'honest' => 'nullable|numeric',
                    'attitude' => 'nullable|numeric',
                    'writing' => 'nullable|numeric',
                    'on_time' => 'nullable|numeric',
                    'orderly' => 'nullable|numeric',
                    'final_test' => 'nullable|numeric',
                ]);

                // technical
                $this->technicalAssessmentService->deleteTechnicalAssessment($id);
                if ($request->technical_aspect != null) {
                    foreach ($request->technical_aspect as $index => $aspect) {
                        $technical_data = [
                            'assessment_id' => $id,
                            'aspect' => $aspect,
                            'score' => $request->technical_score[$index],
                        ];
                        $this->technicalAssessmentService->addTechnicalAssessment($technical_data);
                    }
                }

                // non technical
                $this->nonTechnicalAssessmentService->updateOrCreate([
                    'assessment_id' => $id,
                    'aspect' => 'Kedisiplinan',
                    'score' => $request->dicipline,
                ]);
                $this->nonTechnicalAssessmentService->updateOrCreate([
                    'assessment_id' => $id,
                    'aspect' => 'Kerja Sama',
                    'score' => $request->teamwork,
                ]);
                $this->nonTechnicalAssessmentService->updateOrCreate([
                    'assessment_id' => $id,
                    'aspect' => 'Inisiatif',
                    'score' => $request->initiative,
                ]);
                $this->nonTechnicalAssessmentService->updateOrCreate([
                    'assessment_id' => $id,
                    'aspect' => 'Tanggung Jawab',
                    'score' => $request->responsibility,
                ]);
                $this->nonTechnicalAssessmentService->updateOrCreate([
                    'assessment_id' => $id,
                    'aspect' => 'Jujur dan Santun',
                    'score' => $request->honest,
                ]);

                // final_report
                $this->finalReportAssessmentService->updateOrCreate([
                    'assessment_id' => $id,
                    'aspect' => 'Sikap',
                    'score' => $request->attitude,
                ]);
                $this->finalReportAssessmentService->updateOrCreate([
                    'assessment_id' => $id,
                    'aspect' => 'Tata Tulis',
                    'score' => $request->writing,
                ]);
                $this->finalReportAssessmentService->updateOrCreate([
                    'assessment_id' => $id,
                    'aspect' => 'Ketepatan Waktu',
                    'score' => $request->on_time,
                ]);
                $this->finalReportAssessmentService->updateOrCreate([
                    'assessment_id' => $id,
                    'aspect' => 'Ketertiban',
                    'score' => $request->orderly,
                ]);
                $this->finalReportAssessmentService->updateOrCreate([
                    'assessment_id' => $id,
                    'aspect' => 'Keseluruhan Laporan',
                    'score' => $request->final_report,
                ]);

                // test
                $this->testAssessmentService->updateOrCreate([
                    'assessment_id' => $id,
                    'score' => $request->final_test,
                ]);
            });

            Toastr::addSuccess('Penilaian berhasil ditambahkan!');
        } catch (\Exception $e) {
            Toastr::addError('Penilaian gagal ditambahkan!');
        }
        return redirect()->back();
    }

    public function downloadLaporanAkhir($filename)
    {
        $path = storage_path('app/intern_documents/laporan_akhir/' . $filename);

        if (file_exists($path)) {
            // return response()->download($path);
            return response()->file($path);
        } else {
            return response()->json(['message' => 'File tidak ditemukan'], 404);
        }
    }
}
