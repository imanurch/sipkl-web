<?php

namespace App\Http\Controllers\Admin;

use DateTime;
use Illuminate\Http\Request;
use App\Services\BatchService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\StudentService;
use App\Services\IndustryService;
use App\Services\AssessmentService;
use App\Services\DepartmentService;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\RegistrationDocument;
use App\Services\InternDocumentService;
use App\Services\LogbookService;
use Illuminate\Support\Facades\Hash;
use App\Services\RegistrationService;
use App\Services\RegistrationDocumentService;

class RegistrationAdminController extends Controller
{
    protected
        $registrationService,
        $registrationDocumentService,
        $internshipService,
        $batchService,
        $assessmentService,
        $studentService,
        $logbookService,
        $internDocumentService;

    // Constructor Injection
    public function __construct(
        RegistrationService $registrationService,
        RegistrationDocumentService $registrationDocumentService,
        InternshipService $internshipService,
        BatchService $batchService,
        AssessmentService $assessmentService,
        StudentService $studentService,
        LogbookService $logbookService,
        InternDocumentService $internDocumentService,
    ) {
        $this->registrationService = $registrationService;
        $this->registrationDocumentService = $registrationDocumentService;
        $this->internshipService = $internshipService;
        $this->batchService = $batchService;
        $this->assessmentService = $assessmentService;
        $this->studentService = $studentService;
        $this->logbookService = $logbookService;
        $this->internDocumentService = $internDocumentService;
    }

    public function index(Request $request)
    {
        // batch data
        $batchData = $this->batchService->getAllBatch('');
        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $request->batch ?? ($currentBatch->id ?? '');

        // table filters
        $filters = [
            'search' => $request->searchKeyword ?? '',
            'status' => $request->status ?? '',
            'batch_id' => $request->batch ?? $batch_id,
        ];
        // dd($filters);

        // table data
        $data = $this->registrationService->getRegistration($filters);
        // dd($data);

        // card data
        $unconfirmedRegistration = $this->registrationService->getRegistrationByStatusCount('unconfirmed', $batch_id);
        $acceptedRegistration = $this->registrationService->getRegistrationByStatusCount('accepted', $batch_id);
        $rejectedRegistration = $this->registrationService->getRegistrationByStatusCount('rejected', $batch_id);

        return view('pages.admin.registration', [
            'data' => $data,
            'unconfirmedRegistration' => $unconfirmedRegistration,
            'acceptedRegistration' => $acceptedRegistration,
            'rejectedRegistration' => $rejectedRegistration,
            'filters' => $filters,
            'batchData' => $batchData,
            'pages' => 'registration',
        ]);
    }

    public function downloadFile($type, $filename)
    {
        $path = ($type == 'suratPengantar' ? storage_path('app/registration_document/surat_pengantar/' . $filename) : $path = storage_path('app/registration_document/surat_balasan/' . $filename));

        if (file_exists($path)) {
            return response()->download($path);
        } else {
            return response()->json(['message' => 'File tidak ditemukan'], 404);
        }
    }

    public function confirmStatusRegistration($registrationId, $status)
    {
        // dd($registrationId, $status);
        $this->registrationService->updateStatusRegistration($registrationId, $status);

        $registrationData = $this->registrationService->getRegistrationById($registrationId);

        $data = [
            'group_id' => $registrationData->group_id,
            'industry_id' => $registrationData->industry_id,
            'start_date' => $registrationData->start_date,
            'end_date' => $registrationData->end_date,
            'batch_id' => $registrationData->batch_id,
        ];

        if ($status == 'accept') {
            // tambah registration to internship data
            $newInternship = $this->internshipService->addInternship($data);
            // dd($newInternship->group->name);

            foreach ($newInternship->group->groupMember as $member) {
                $newInternId = $member->student->id;

                // buat tempat di assessment
                $this->assessmentService->addAssessment([
                    'student_id' => $newInternId,
                    'internship_id' => $newInternship->id,
                ]);

                // buat tempat di logbook
                $logbook_start_date = new DateTime($newInternship->start_date);
                $logbook_end_date = new DateTime($newInternship->end_date);

                while ($logbook_start_date <= $logbook_end_date) {
                    $current_start = clone $logbook_start_date;

                    $current_end = clone $logbook_start_date;
                    $current_end->modify('+6 days');

                    if ($current_end > $logbook_end_date) {
                        $current_end = clone $logbook_end_date;
                    }

                    $logbook_data = [
                        'student_id'    => $newInternId,
                        'internship_id' => $newInternship->id,
                        'start_date'    => $current_start->format('Y-m-d'),
                        'end_date'      => $current_end->format('Y-m-d')
                    ];

                    $this->logbookService->addLogbook($logbook_data);

                    $logbook_start_date = clone $current_end;
                    $logbook_start_date->modify('+1 day');
                }

                // buat document intern (surat jalan)
                $data = [
                    'title' => 'Contoh Dokumen Surat Jalan',
                    'date'  => date('d-m-Y'),
                ];

                $pdf = Pdf::loadView('document_templates/surat_pengantar_template', $data);
                $filename = 'surat_jalan_' . time() . '.pdf';

                $path = storage_path('app/intern_documents/surat_jalan/' . $filename);
                $pdf->save($path);
                
                $this->internDocumentService->addInternDocument([
                    'student_id' => $newInternId,
                    'internship_id' => $newInternship->id,
                    'type' => 'surat jalan',
                    'url' => $filename,
                ]);
            }
        }

        return back()->withInput()->with('success', 'Operasi berhasil!');
    }

    public function generateSuratPengantar($registration_id)
    {
        // Data yang akan dikirim ke view
        $data = [
            'title' => 'Contoh Dokumen PDF',
            'date'  => date('d-m-Y'),
        ];

        // Memuat view dan mengkonversinya menjadi PDF
        $pdf = Pdf::loadView('document_templates/surat_pengantar_template', $data);

        // $path_file_pengantar = $pdf->store('registration_document/surat_pengantar');
        // $filename = basename($path_file_pengantar);
        // dd($filename);

        // Buat nama file dinamis, misalnya dengan timestamp
        $filename = 'surat_pengantar_' . time() . '.pdf';

        // Tentukan path lengkap untuk menyimpan file di storage/app
        $path = storage_path('app/registration_document/surat_pengantar/' . $filename);

        // Pastikan folder 'registration_document/surat_pengantar' sudah ada
        $pdf->save($path);
        // dd($filename);
        $this->registrationDocumentService->updateRegistrationDocument($registration_id, 'surat pengantar', $filename);

        // return response()->download($path);

        return back();


        // Cara untuk download
        // return $pdf->download('dokumen.pdf');

        // Atau, untuk menampilkan PDF di browser:
        // return $pdf->stream('dokumen.pdf');
    }

    public function destroy($id)
    {
        $this->registrationService->deleteRegistration($id);
        return back();
    }
}
