<?php

namespace App\Http\Controllers\Admin;

use DateTime;
use App\Models\Logbook;
use Illuminate\Http\Request;
use App\Services\BatchService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\LogbookService;
use App\Services\StudentService;
use App\Services\IndustryService;
use Illuminate\Support\Facades\DB;
use App\Services\AssessmentService;
use App\Services\DepartmentService;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use App\Models\RegistrationDocument;
use Illuminate\Support\Facades\Hash;
use App\Services\RegistrationService;
use App\Services\InternDocumentService;
use Flasher\Toastr\Laravel\Facade\Toastr;
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
        foreach ($data as $dt) {
            if ($dt->RegistrationDocument) {
                foreach ($dt->registrationDocument as $doc) {
                    if ($doc->type == 'surat pengantar') {
                        $dt->surat_pengantar = $doc->url != '' ? $doc->url : null;
                    } else if ($doc->type == 'surat balasan') {
                        $dt->surat_balasan = $doc->url != '' ? $doc->url : null;
                    } else if ($doc->type == 'ucapan terima kasih') {
                        $dt->ucapan_terima_kasih = $doc->url != '' ? $doc->url : null;
                    }
                }
            }
            $dt->status = $dt->status == '0' ? 'Belum Dikonfirmasi' : ($dt->status == '1' ? 'Diterima' : 'Ditolak');
        }
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
        try {
            DB::transaction(function () use ($registrationId, $status) {
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

                        // buat ucapan terima kasih
                        $data = [
                            'title' => 'Contoh Dokumen Surat Terima kasih',
                            'date'  => date('d-m-Y'),
                        ];

                        $pdf = Pdf::loadView('document_templates/surat_pengantar_template', $data);
                        $filename = 'ucapan_terima_kasih_' . time() . '.pdf';

                        $path = storage_path('app/registration_document/ucapan_terima_kasih/' . $filename);
                        $pdf->save($path);

                        $this->registrationDocumentService->updateRegistrationDocument($registrationId, 'ucapan terima kasih', $filename);
                    }
                }
            });
            Toastr::addSuccess('Registrasi berhasil dikonfirmasi!');
        } catch (\Exception $e) {
            Toastr::addError('Registrasi gagal dikonfirmasi!');
        }
        return redirect()->back();
    }

    public function updateStatusRegistration(Request $request, $registrationId)
    {
        // dd($request->status, $registrationId);
        $newStatus = $request->status == 'accept' ? '1' : '2';
        $registrationData = $this->registrationService->getRegistrationById($registrationId);
        // dd($registrationData);
        try {
            DB::transaction(function () use ($registrationId, $registrationData, $newStatus) {
                if ($registrationData->status != $newStatus) {
                    // dd($newStatus);
                    if ($newStatus == '1') {
                        $this->registrationService->updateStatusRegistration($registrationId, 'accept');

                        $data = [
                            'group_id' => $registrationData->group_id,
                            'industry_id' => $registrationData->industry_id,
                            'start_date' => $registrationData->start_date,
                            'end_date' => $registrationData->end_date,
                            'batch_id' => $registrationData->batch_id,
                        ];

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

                            // buat ucapan terima kasih
                            $data = [
                                'title' => 'Contoh Dokumen Surat Terima kasih',
                                'date'  => date('d-m-Y'),
                            ];

                            $pdf = Pdf::loadView('document_templates/surat_pengantar_template', $data);
                            $filename = 'ucapan_terima_kasih_' . time() . '.pdf';

                            $path = storage_path('app/registration_document/ucapan_terima_kasih/' . $filename);
                            $pdf->save($path);

                            $this->registrationDocumentService->updateRegistrationDocument($registrationId, 'ucapan terima kasih', $filename);
                        }
                    } elseif ($newStatus == '2') {
                        // update status registration
                        $this->registrationService->updateStatusRegistration($registrationId, 'reject');

                        // delete internship
                        $internship_id = $this->internshipService->getInternshipByGroupId($registrationData->group_id)->id;
                        $this->internshipService->deleteInternship($internship_id);

                        // delete ucapan terima kasih
                        $this->registrationDocumentService->updateRegistrationDocument($registrationId, 'ucapan terima kasih', null);
                    }
                }
            });
            Toastr::addSuccess('Status berhasil diubah!');
        } catch (\Exception $e) {
            Toastr::addError('Status gagal diubah!');
        }
        return redirect()->back();
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

        // return response()->download($path);

        try {
            $this->registrationDocumentService->updateRegistrationDocument($registration_id, 'surat pengantar', $filename);
            Toastr::addSuccess('Surat Pengantar berhasil dibuat. Silahkan refresh halaman!');
        } catch (\Exception $e) {
            Toastr::addError('Surat Pengantar gagal dibuat. Silahkan refresh halaman!');
        }
        return redirect()->back();

        // Cara untuk download
        // return $pdf->download('dokumen.pdf');

        // Atau, untuk menampilkan PDF di browser:
        // return $pdf->stream('dokumen.pdf');
    }

    public function destroy($id)
    {
        try {
            $registrationData = $this->registrationService->getRegistrationById($id);
            DB::transaction(function () use ($registrationData, $id) {
                // delete registration
                $this->registrationService->deleteRegistration($id);
                // delete internship
                $internship = $this->internshipService->getInternshipByGroupId($registrationData->group_id);
                if ($internship != null) {
                    $internship_id = $internship->id;
                    $this->internshipService->deleteInternship($internship_id);
                }
            });
            Toastr::addSuccess('Data registrasi berhasil dihapus!');
        } catch (\Exception $e) {
            Toastr::addError('Data registrasi gagal dihapus!');
        }
        return redirect()->back();
    }
}
