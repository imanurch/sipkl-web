<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DateFormatHelper;
use Illuminate\Http\Request;
use App\Services\BatchService;
use Illuminate\Support\Facades\DB;
use App\Services\AssessmentService;
use App\Http\Controllers\Controller;
use App\Services\TestAssessmentService;
use App\Services\InternshipOutputService;
use Flasher\Toastr\Laravel\Facade\Toastr;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Services\TechnicalAssessmentService;
use App\Http\Requests\UpdateAssessmentRequest;
use App\Services\FinalReportAssessmentService;
use App\Services\NonTechnicalAssessmentService;
use App\Services\CalculateAssessmentScoreService;

class AssessmentAdminController extends Controller
{
    protected $assessmentService,
        $batchService,
        $technicalAssessmentService,
        $nonTechnicalAssessmentService,
        $finalReportAssessmentService,
        $testAssessmentService,
        $calculateAssessmentScoreService,
        $internshipOutputService;

    // Constructor Injection
    public function __construct(
        AssessmentService $assessmentService,
        BatchService $batchService,
        TechnicalAssessmentService $technicalAssessmentService,
        NonTechnicalAssessmentService $nonTechnicalAssessmentService,
        FinalReportAssessmentService $finalReportAssessmentService,
        TestAssessmentService $testAssessmentService,
        CalculateAssessmentScoreService $calculateAssessmentScoreService,
        InternshipOutputService $internshipOutputService
    ) {
        $this->assessmentService = $assessmentService;
        $this->batchService = $batchService;
        $this->technicalAssessmentService = $technicalAssessmentService;
        $this->nonTechnicalAssessmentService = $nonTechnicalAssessmentService;
        $this->finalReportAssessmentService = $finalReportAssessmentService;
        $this->testAssessmentService = $testAssessmentService;
        $this->calculateAssessmentScoreService = $calculateAssessmentScoreService;
        $this->internshipOutputService = $internshipOutputService;
    }

    /**
     * Display a list of assessments with filters, counts, and data for the active batch.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $batch_id = $this->batchService->getRelevantBatch($request->batch);

        // Set up table filters for searching and batch selection
        $batchData = $this->batchService->getAllBatch('');
        $filters = [
            'search' => $request->searchKeyword ?? '',
            'batch_id' => $batch_id,
        ];

        // Get counts for not assessed, passed, and failed assessments
        $countNotAssessed = $this->assessmentService->getNotAssessedCount($batch_id);
        $countPass = $this->assessmentService->getAssessedCount($batch_id, 'pass');
        $countNotPass = $this->assessmentService->getAssessedCount($batch_id, 'notPass');

        // Fetch assessment data based on the filters
        $data = $this->assessmentService->getAssessment($filters);

        // Calculate final internship scores and check for complete internship outputs
        foreach ($data as $dt) {
            $this->calculateAssessmentScoreService->calculateInternshipScore($dt);
            $dt->isCompleteOutput = $this->internshipOutputService->OutputInternshipIsCompleteCheck($dt->internship_id, $dt->student_id);
        }

        // Return the assessment view with all necessary data
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

    /**
     * Update the assessment for a student.
     *
     * @param UpdateAssessmentRequest $request
     * @param int $id Assessment ID
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Export the assessment data to an Excel file.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $this->setupSheetExport($sheet);

        $data = $this->assessmentService->getAssessmentByBatch($request->batch_id);

        $this->fillDataExport($sheet, $data);

        $filename = "data_assessment_export.xlsx";
        try {
            return response()->streamDownload(function () use ($spreadsheet) {
                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            }, $filename);
        } catch (\Exception $e) {
            Toastr::addError('Export gagal!');
            return redirect()->back();
        }
    }

    /**
     * Setup the sheet for exporting data (merge cells and set column titles)
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     */
    private function setupSheetExport($sheet)
    {
        $sheet->mergeCells('A1:A2');
        $sheet->mergeCells('B1:B2');
        $sheet->mergeCells('C1:C2');
        $sheet->mergeCells('D1:D2');
        $sheet->mergeCells('E1:E2');
        $sheet->mergeCells('F1:F2');
        $sheet->mergeCells('G1:G2');
        $sheet->mergeCells('H1:H2');
        $sheet->mergeCells('I1:I2');
        $sheet->mergeCells('J1:N1');
        $sheet->mergeCells('O1:S1');
        $sheet->mergeCells('T1:T2');
        $sheet->mergeCells('U1:U2');
        $sheet->mergeCells('V1:V2');

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'NAMA');
        $sheet->setCellValue('C1', 'NIS');
        $sheet->setCellValue('D1', 'NISN');
        $sheet->setCellValue('E1', 'JURUSAN');
        $sheet->setCellValue('F1', 'TAHUN AJARAN');
        $sheet->setCellValue('G1', 'INDUSTRI');
        $sheet->setCellValue('H1', 'ALAMAT INDUSTRI');
        $sheet->setCellValue('I1', 'NILAI TEKNIS');
        $sheet->setCellValue('J1', 'NILAI NON TEKNIS');
        $sheet->setCellValue('J2', 'KEDISIPLINAN');
        $sheet->setCellValue('K2', 'KERJA SAMA');
        $sheet->setCellValue('L2', 'INISIATIF');
        $sheet->setCellValue('M2', 'TANGGUNG JAWAB');
        $sheet->setCellValue('N2', 'JUJUR DAN SANTUN');
        $sheet->setCellValue('O1', 'NILAI LAPORAN AKHIR');
        $sheet->setCellValue('O2', 'SIKAP');
        $sheet->setCellValue('P2', 'TATA TULIS');
        $sheet->setCellValue('Q2', 'KETEPATAN WAKTU');
        $sheet->setCellValue('R2', 'KETERTIBAN');
        $sheet->setCellValue('S2', 'LAPORAN PKL KESELURUHAN');
        $sheet->setCellValue('T1', 'NILAI UJIAN PKL');
        $sheet->setCellValue('U1', 'NILAI AKHIR PKL');
        $sheet->setCellValue('V1', 'STATUS PKL');

        foreach (range('A', 'V') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    /**
     * Fill the sheet with the assessment data
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @param array $data The assessment data to export
     */
    private function fillDataExport($sheet, $data)
    {
        foreach ($data as $dt) {
            // calculate internship score
            $this->calculateAssessmentScoreService->calculateInternshipScore($dt);
        }

        $row = 3;
        $num = 1;

        foreach ($data as $dt) {
            $sheet->setCellValue('A' . $row, $num);
            $sheet->setCellValue('B' . $row, $dt->student->name);
            $sheet->setCellValue('C' . $row, $dt->student->nis);
            $sheet->setCellValue('D' . $row, $dt->student->nisn);
            $sheet->setCellValue('E' . $row, $dt->student->department->name);
            $sheet->setCellValue('F' . $row, DateFormatHelper::academicYearFormat($dt->student->year));
            $sheet->setCellValue('G' . $row, $dt->internship->industry->name);
            $sheet->setCellValue('H' . $row, $dt->internship->industry->address);
            $sheet->setCellValue('I' . $row, $dt->technical_aspect != null ? rtrim($dt->technical_aspect) : '');
            $sheet->getStyle('I' . $row)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('J' . $row, $dt->non_technical_aspect['Kedisiplinan'] ?? '');
            $sheet->setCellValue('K' . $row, $dt->non_technical_aspect['Kerja Sama'] ?? '');
            $sheet->setCellValue('L' . $row, $dt->non_technical_aspect['Inisiatif'] ?? '');
            $sheet->setCellValue('M' . $row, $dt->non_technical_aspect['Tanggung Jawab'] ?? '');
            $sheet->setCellValue('N' . $row, $dt->non_technical_aspect['Jujur dan Santun'] ?? '');
            $sheet->setCellValue('O' . $row, $dt->final_report['Sikap'] ?? '');
            $sheet->setCellValue('P' . $row, $dt->final_report['Tata Tulis'] ?? '');
            $sheet->setCellValue('Q' . $row, $dt->final_report['Ketepatan Waktu'] ?? '');
            $sheet->setCellValue('R' . $row, $dt->final_report['Ketertiban'] ?? '');
            $sheet->setCellValue('S' . $row, $dt->final_report['Keseluruhan Laporan'] ?? '');
            $sheet->setCellValue('T' . $row, $dt->test_assessment != null ? $dt->test_assessment->score : '');
            $sheet->setCellValue('U' . $row, $dt->internship_score ?? 'Nilai Belum Lengkap');
            $sheet->setCellValue('V' . $row, $dt->internship_status ?? 'Nilai Belum Lengkap');
            $row++;
            $num++;
        }
    }
}
