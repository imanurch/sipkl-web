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
use App\Services\SignatureService;
use Illuminate\Support\Facades\DB;
use App\Services\AssessmentService;
use App\Services\DepartmentService;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use App\Models\RegistrationDocument;
use App\Notifications\RegistrationDocumentNotification;
use Illuminate\Support\Facades\Hash;
use App\Services\RegistrationService;
use App\Services\SchoolProfileService;
use App\Services\InternDocumentService;
use Illuminate\Support\Facades\Storage;
use Flasher\Toastr\Laravel\Facade\Toastr;
use Illuminate\Support\Facades\Notification;
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
        $internDocumentService,
        $signatureService,
        $schoolProfileService;

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
        SignatureService $signatureService,
        SchoolProfileService $schoolProfileService
    ) {
        $this->registrationService = $registrationService;
        $this->registrationDocumentService = $registrationDocumentService;
        $this->internshipService = $internshipService;
        $this->batchService = $batchService;
        $this->assessmentService = $assessmentService;
        $this->studentService = $studentService;
        $this->logbookService = $logbookService;
        $this->internDocumentService = $internDocumentService;
        $this->signatureService = $signatureService;
        $this->schoolProfileService = $schoolProfileService;
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

        // table data
        $data = $this->registrationService->getRegistration($filters);
        foreach ($data as $dt) {
            if ($dt->RegistrationDocument) {
                foreach ($dt->registrationDocument as $doc) {
                    if ($doc->type == 'surat permohonan') {
                        $dt->surat_permohonan = $doc->url != '' ? $doc->url : null;
                    } else if ($doc->type == 'surat balasan') {
                        $dt->surat_balasan = $doc->url != '' ? $doc->url : null;
                    }
                }
            }
            $dt->status = $dt->status == '0' ? 'Belum Dikonfirmasi' : ($dt->status == '1' ? 'Diterima' : 'Ditolak');
        }

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
        $path = ($type == 'suratPermohonan' ? storage_path('app/registration_document/surat_permohonan/' . $filename) : $path = storage_path('app/registration_document/surat_balasan/' . $filename));

        if (file_exists($path)) {
            // return response()->download($path);
            return response()->file($path);
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

                    foreach ($newInternship->group->groupMember as $member) {
                        $newIntern = $member->student;
                        $newInternId = $newIntern->id;

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

                        // // buat document intern (surat jalan)
                        // // $principal_data = $this->signatureService->getPrincipalSignature();
                        // $school_profile = $this->schoolProfileService->getSchoolProfile();
                        // $data = [
                        //     'principal_name' => $school_profile->principal_name,
                        //     'principal_nip' => $school_profile->principal_nip,
                        //     'principal_signature'  => $school_profile->principal_signature,
                        //     'intern_name' => $newIntern->name,
                        //     'intern_nis' => $newIntern->nis,
                        //     // 'batch' => $principal_data->nip,
                        //     'internship_start_date' => $newInternship->start_date,
                        //     'internship_end_date' => $newInternship->end_date,
                        //     'industry_name' => $newInternship->industry->name,
                        //     'industry_address' => $newInternship->industry->address,
                        //     // 'intern_transport' => $principal_data->nip,
                        //     'create_date'  => date('d-m-Y'),
                        // ];

                        // $pdf = Pdf::loadView('document_templates/surat_jalan', $data);
                        // $filename = 'surat_jalan_' . time() . '.pdf';

                        // $path = storage_path('app/intern_documents/surat_jalan/' . $filename);
                        // $pdf->save($path);

                        // // return $pdf->stream('dokumen.pdf');

                        // $this->internDocumentService->addInternDocument([
                        //     'student_id' => $newInternId,
                        //     'internship_id' => $newInternship->id,
                        //     'type' => 'surat jalan',
                        //     'url' => $filename,
                        // ]);
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

                            // // buat document intern (surat jalan)
                            // $data = [
                            //     'title' => 'Contoh Dokumen Surat Jalan',
                            //     'date'  => date('d-m-Y'),
                            // ];

                            // $pdf = Pdf::loadView('document_templates/surat_pengantar_template', $data);
                            // $filename = 'surat_jalan_' . time() . '.pdf';

                            // $path = storage_path('app/intern_documents/surat_jalan/' . $filename);
                            // $pdf->save($path);

                            // $this->internDocumentService->addInternDocument([
                            //     'student_id' => $newInternId,
                            //     'internship_id' => $newInternship->id,
                            //     'type' => 'surat jalan',
                            //     'url' => $filename,
                            // ]);
                        }
                    } elseif ($newStatus == '2') {
                        // update status registration
                        $this->registrationService->updateStatusRegistration($registrationId, 'reject');

                        // delete internship
                        $internship_id = $this->internshipService->getInternshipByGroupId($registrationData->group_id)->id;
                        $this->internshipService->deleteInternship($internship_id);
                    }
                }
            });
            Toastr::addSuccess('Status berhasil diubah!');
        } catch (\Exception $e) {
            Toastr::addError('Status gagal diubah!');
        }
        return redirect()->back();
    }

    // public function generateSuratPermohonan($registration_id)
    public function generateDocument(Request $request)
    {
        // dd($request->all());
        $registration_data = $this->registrationService->getRegistrationById($request->registration_id);
        $school_profile = $this->schoolProfileService->getSchoolProfile();

        $data = [
            'school_phone_num'  => $school_profile->phone_num,
            'school_website'  => $school_profile->website,
            'school_email'  => $school_profile->email,
            'create_date' => date('d F Y'),
            'letter_num'  => $request->letter_num,
            'industry_name'  => $registration_data->industry->name,
            'industry_address'  => $registration_data->industry->address,
            'academic_year'  => $registration_data->batch->year . '/' . $registration_data->batch->year + 1,
            'internship_start_month'  => date('F', strtotime($registration_data->start_date)),
            'internship_end_month'  => date('F', strtotime($registration_data->end_date)),
            'internship_year'  => $registration_data->batch->year,
            'principal_name'  => $school_profile->principal_name,
            'principal_nip'  => $school_profile->principal_nip,
            'principal_signature'  => $school_profile->principal_signature,
            'intern_group_data' => $registration_data->group->groupMember,
        ];

        $pdf = Pdf::loadView('document_templates/surat_permohonan_pkl', $data);
        $filename = 'surat_permohonan_' . time() . '.pdf';
        $path = storage_path('app/registration_document/surat_permohonan/' . $filename);

        $pdf->save($path);

        try {
            $this->registrationDocumentService->updateRegistrationDocument($request->registration_id, 'surat permohonan', $filename);

            $registrationDocumentData = $this->registrationDocumentService->getRegistrationDocumentByRegistrationId($request->registration_id);
            $users = [];
            foreach ($registrationDocumentData as $dt) {
                foreach ($dt->registration->group->groupMember as $member) {
                    $user = $member->student->user;
                    $users[] = $user;
                }
            }
            foreach ($users as $user) {
                if ($user->email_verified_at != null) {
                    Notification::send($user, new RegistrationDocumentNotification());
                }
            }

            Toastr::addSuccess('Surat Permohonan berhasil dibuat. Silahkan refresh halaman!');
        } catch (\Exception $e) {
            Toastr::addError('Surat Permohonan gagal dibuat. Silahkan refresh halaman!');
        }
        return redirect()->back();
    }

    public function destroy($id)
    {
        $doc = $this->registrationDocumentService->getRegistrationDocumentByRegistrationId($id);
        foreach ($doc as $dt) {
            $filename = $dt->url;
            if ($dt->type == "surat permohonan") {
                Storage::delete('registration_document/surat_permohonan/' . $filename);
            } elseif ($dt->type == "surat balasan") {
                Storage::delete('registration_document/surat_balasan/' . $filename);
            }
        }
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
