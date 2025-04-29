<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\LogbookService;
use App\Services\StudentService;
use App\Helpers\DateFormatHelper;
use App\Services\DownloadService;
use Illuminate\Support\Facades\DB;
use App\Services\AssessmentService;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use App\Services\RegistrationService;
use App\Services\SchoolProfileService;
use App\Services\DeleteDocumentService;
use App\Services\InternDocumentService;
use App\Services\DocumentGenerateService;
use Flasher\Toastr\Laravel\Facade\Toastr;
use Illuminate\Support\Facades\Notification;
use App\Services\RegistrationDocumentService;
use App\Notifications\RegistrationDocumentNotification;

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
        $schoolProfileService,
        $downloadService,
        $documentGenerateService,
        $deleteDocumentService;

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
        SchoolProfileService $schoolProfileService,
        DownloadService $downloadService,
        DocumentGenerateService $documentGenerateService,
        DeleteDocumentService $deleteDocumentService
    ) {
        $this->registrationService = $registrationService;
        $this->registrationDocumentService = $registrationDocumentService;
        $this->internshipService = $internshipService;
        $this->batchService = $batchService;
        $this->assessmentService = $assessmentService;
        $this->studentService = $studentService;
        $this->logbookService = $logbookService;
        $this->internDocumentService = $internDocumentService;
        $this->schoolProfileService = $schoolProfileService;
        $this->downloadService = $downloadService;
        $this->documentGenerateService = $documentGenerateService;
        $this->deleteDocumentService = $deleteDocumentService;
    }

    public function index(Request $request)
    {
        // batch data
        $batchData = $this->batchService->getAllBatch('');
        $batch_id = $this->batchService->getRelevantBatch($request->batch);

        // table filters
        $filters = [
            'search' => $request->searchKeyword ?? '',
            'status' => $request->status ?? '',
            'batch_id' => $batch_id,
        ];

        // table data
        $data = $this->registrationService->getRegistration($filters);
        foreach ($data as $dt) {
            $dt->start_date = DateFormatHelper::dateFormat($dt->start_date);
            $dt->end_date = DateFormatHelper::dateFormat($dt->end_date);

            if ($dt->RegistrationDocument) {
                foreach ($dt->registrationDocument as $doc) {
                    $url = ($doc->url != '' ? $doc->url : null);
                    if ($doc->type == 'surat permohonan') {
                        $dt->surat_permohonan = $url;
                    } else if ($doc->type == 'surat balasan') {
                        $dt->surat_balasan = $url;
                    }
                }
            }
            $dt->status = match ($dt->status) {
                '0' => 'Belum Dikonfirmasi',
                '1' => 'Diterima',
                default => 'Ditolak',
            };
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
        return $this->downloadService->monitoringDocumentDownload($type, $filename);
    }

    public function generateDocument(Request $request)
    {
        $registration_data = $this->registrationService->getRegistrationById($request->registration_id);
        $school_profile = $this->schoolProfileService->getSchoolProfile();
        $intern_group_data = $registration_data->group->groupMember;

        $data = [
            'school_phone_num'  => $school_profile->phone_num,
            'school_website'  => $school_profile->website,
            'school_email'  => $school_profile->email,
            'create_date' => DateFormatHelper::dateFormat(date('d M Y')),
            'letter_num'  => $request->letter_num,
            'industry_name'  => $registration_data->industry->name,
            'industry_address'  => $registration_data->industry->address,
            'academic_year'  => DateFormatHelper::academicYearFormat($registration_data->batch->year),
            'internship_start_month'  => DateFormatHelper::monthFormat($registration_data->start_date),
            'internship_end_month'  => DateFormatHelper::monthFormat($registration_data->end_date),
            'internship_year'  => $registration_data->batch->year,
            'principal_name'  => $school_profile->principal_name,
            'principal_nip'  => $school_profile->principal_nip,
            'principal_signature'  => $school_profile->principal_signature,
            'intern_group_data' => $intern_group_data,
        ];

        $filename = $this->documentGenerateService->registrationDocumentGenerate($data);

        try {
            $this->registrationDocumentService->updateRegistrationDocument($request->registration_id, 'surat permohonan', $filename);

            $this->notification($intern_group_data);

            Toastr::addSuccess('Surat Permohonan berhasil dibuat. Silahkan refresh halaman!');
        } catch (\Exception $e) {
            Toastr::addError('Surat Permohonan gagal dibuat. Silahkan refresh halaman!');
        }
        return redirect()->back();
    }

    private function notification($data)
    {
        $users = [];
        foreach ($data as $dt) {
            $user = $dt->student->user;
            $users[] = $user;
        }
        foreach ($users as $user) {
            if ($user->email_verified_at != null) {
                Notification::send($user, new RegistrationDocumentNotification());
            }
        }
    }

    public function destroy($id)
    {
        $doc = $this->registrationDocumentService->getRegistrationDocumentByRegistrationId($id);
        try {
            if ($doc != null) {
                foreach ($doc as $dt) {
                    $this->deleteDocumentService->deleteRegistrationDocument($dt->type, $dt->url);
                }
            }
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
