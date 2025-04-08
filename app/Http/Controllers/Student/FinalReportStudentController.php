<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\StudentService;
use Illuminate\Support\Facades\DB;
use App\Services\AssessmentService;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\InternDocumentService;
use Flasher\Toastr\Laravel\Facade\Toastr;

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
        $isIntern = $this->internshipService->getInternshipByStudentId($batch_id, $student_id) != null ? true : false;

        $data = $this->internDocumentService->getInternDocumentByStudentId($student_id, 'laporan akhir');

        if ($data != null) {
            $final_report_assessment = $this->assessmentService->getAssessmentByStudentIdAndInternshipId($student_id, $data->internship_id)->final_report_assessment;

            if ($final_report_assessment != null) {
                foreach ($final_report_assessment as $dt) {
                    if ($dt->score == null && $dt->score != 0) {
                        $data->isAssessed = false;
                        break;
                    } else {
                        $data->isAssessed = true;
                    }
                }
            } else {
                $data->isAssessed = false;
            }
        }

        return view('pages.student.final_report', [
            'data' => $data,
            'isIntern' => $isIntern,
            'pages' => 'finalReport',
        ]);
    }

    public function store(Request $request)
    {
        $student_id = session('user_bio')->id;
        $batch_id = $this->batchService->getBatchByStatus('active')->id;
        $internship_id = $this->internshipService->getInternshipByStudentId($batch_id, $student_id)->id;

        try {
            $validatedData = $request->validate([
                'laporan_akhir' => 'required|mimes:pdf',
            ]);
            DB::transaction(function () use ($validatedData, $student_id, $internship_id) {
                $path_file_balasan = $validatedData['laporan_akhir']->store('intern_documents/laporan_akhir');
                $filename = basename($path_file_balasan);

                $data = [
                    'student_id' => $student_id,
                    'internship_id' => $internship_id,
                    'type' => 'laporan akhir',
                    'url' => $filename,
                ];

                $this->internDocumentService->addInternDocument($data);
            });
            Toastr::addSuccess('Laporan Akhir berhasil diunggah!');
        } catch (\Exception $e) {
            Toastr::addError('Laporan Akhir gagal diunggah!');
        }
        return redirect()->back();
    }

    public function downloadLaporanAkhir($filename)
    {
        $path = storage_path('app/intern_documents/laporan_akhir/' . $filename);

        if (file_exists($path)) {
            return response()->download($path);
        } else {
            Toastr::addError('File tidak ditemukan!');
            return redirect()->back();
        }
    }
}
