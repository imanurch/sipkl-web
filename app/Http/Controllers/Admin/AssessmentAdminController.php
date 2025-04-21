<?php

namespace App\Http\Controllers\Admin;

use Log;
use Illuminate\Http\Request;
use App\Services\AdminService;
use App\Services\BatchService;
use App\Services\LogbookService;
use Illuminate\Support\Facades\DB;
use App\Services\AssessmentService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Services\InternDocumentService;
use App\Services\TestAssessmentService;
use Flasher\Toastr\Laravel\Facade\Toastr;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Services\TechnicalAssessmentService;
use App\Services\FinalReportAssessmentService;
use App\Services\NonTechnicalAssessmentService;

class AssessmentAdminController extends Controller
{
    protected $assessmentService,
        $batchService,
        $internDocumentService,
        $logbookService,
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
        TechnicalAssessmentService $technicalAssessmentService,
        NonTechnicalAssessmentService $nonTechnicalAssessmentService,
        FinalReportAssessmentService $finalReportAssessmentService,
        TestAssessmentService $testAssessmentService
    ) {
        $this->assessmentService = $assessmentService;
        $this->batchService = $batchService;
        $this->internDocumentService = $internDocumentService;
        $this->logbookService = $logbookService;
        $this->technicalAssessmentService = $technicalAssessmentService;
        $this->nonTechnicalAssessmentService = $nonTechnicalAssessmentService;
        $this->finalReportAssessmentService = $finalReportAssessmentService;
        $this->testAssessmentService = $testAssessmentService;
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
                    // dd($aspect);
                    $dt->$aspect = $aspect_score->score;
                    // dd($dt->non_technical_assessment->Kedisiplinan);
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
                    // dd($aspect);
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

            // cek final report
            $isCompleteFinalReport = $this->internDocumentService->checkIsCompleteFinalReportByInternshipAndStudentId($dt->internship_id, $dt->student_id);
            
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
                    'final_report' => 'nullable|numeric',
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
                if($request->final_test != null){
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

    public function export(Request $request)
    {
        // dd($request->all());
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'NAMA');
        $sheet->setCellValue('C1', 'NIS');
        $sheet->setCellValue('D1', 'NISN');
        $sheet->setCellValue('E1', 'JURUSAN');
        $sheet->setCellValue('F1', 'TAHUN AJARAN');
        $sheet->setCellValue('G1', 'INDUSTRI');
        $sheet->setCellValue('H1', 'ALAMAT INDUSTRI');
        $sheet->setCellValue('I1', 'NILAI PKL');
        $sheet->setCellValue('J1', 'STATUS PKL');

        $data = $this->assessmentService->getAssessmentByBatch($request->batch_id);

        $internship_score = 0;

        foreach ($data as $dt) {
            $technical_score = 0;
            $non_technical_score = 0;
            $final_report_score = 0;

            if (
                count($dt->technical_assessment) > 0 &&
                $this->technicalAssessmentService->isTechnicalAssessmentComplete($dt->id) == true &&
                $this->nonTechnicalAssessmentService->isNonTechnicalAssessmentComplete($dt->id) == true &&
                $this->finalReportAssessmentService->isFinalReportAssessmentComplete($dt->id) == true &&
                $this->testAssessmentService->isTestAssessmentComplete($dt->id) == true
            ) {
                // technical
                foreach ($dt->technical_assessment as $aspect_score) {
                    $technical_score += $aspect_score->score;
                }
                $technical_score_average = $technical_score / count($dt->technical_assessment);

                // non technical
                foreach ($dt->non_technical_assessment as $aspect_score) {
                    $non_technical_score += $aspect_score->score;
                }
                $non_technical_score_average = $non_technical_score / count($dt->non_technical_assessment);

                // final report
                foreach ($dt->final_report_assessment as $aspect_score) {
                    $final_report_score += $aspect_score->score;
                }
                $final_report_score_average = $final_report_score / count($dt->final_report_assessment);

                // final score internship
                $dt->internship_score = round((($technical_score_average + $non_technical_score_average + $final_report_score_average + $dt->test_assessment->score) / 4), 2);
                // cek kelulusan
                if ($dt->internship_score >= 75) {
                    $dt->internship_status = 'Lulus';
                } else {
                    $dt->internship_status = 'Tidak Lulus';
                }
            } else {
                $dt->internship_score = 'Nilai Belum Lengkap';
                $dt->internship_status = 'Nilai Belum Lengkap';
            }
        }

        $row = 2;
        $num = 1;

        foreach ($data as $dt) {
            $sheet->setCellValue('A' . $row, $num);
            $sheet->setCellValue('B' . $row, $dt->student->name);
            $sheet->setCellValue('C' . $row, $dt->student->nis);
            $sheet->setCellValue('D' . $row, $dt->student->nisn);
            $sheet->setCellValue('E' . $row, $dt->student->department->name);
            $sheet->setCellValue('F' . $row, $dt->student->year . '/' . $dt->student->year + 1);
            $sheet->setCellValue('G' . $row, $dt->internship->industry->name);
            $sheet->setCellValue('H' . $row, $dt->internship->industry->address);
            $sheet->setCellValue('I' . $row, $dt->internship_score);
            $sheet->setCellValue('J' . $row, $dt->internship_status);
            $row++;
            $num++;
        }

        $filename = "data_assessment_export.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
