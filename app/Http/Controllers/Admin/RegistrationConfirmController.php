<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\LogbookService;
use App\Services\StudentService;
use Illuminate\Support\Facades\DB;
use App\Services\AssessmentService;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use App\Services\RegistrationService;
use Flasher\Toastr\Laravel\Facade\Toastr;

class RegistrationConfirmController extends Controller
{
    protected
        $registrationService,
        $internshipService,
        $assessmentService,
        $studentService,
        $logbookService;

    // Constructor Injection
    public function __construct(
        RegistrationService $registrationService,
        InternshipService $internshipService,
        AssessmentService $assessmentService,
        StudentService $studentService,
        LogbookService $logbookService
    ) {
        $this->registrationService = $registrationService;
        $this->internshipService = $internshipService;
        $this->assessmentService = $assessmentService;
        $this->studentService = $studentService;
        $this->logbookService = $logbookService;
    }

    private function handleAcceptRegistration(array $data)
    {
        $newInternship = $this->internshipService->addInternship($data);

        foreach ($newInternship->group->groupMember as $member) {
            $newInternId = $member->student->id;

            // buat tempat di assessment
            $this->assessmentService->addAssessment([
                'student_id' => $newInternId,
                'internship_id' => $newInternship->id,
            ]);

            // buat tempat di logbook
            $this->logbookService->addLogbook($newInternship, $newInternId);
        }
    }

    public function confirmStatusRegistration($registrationId, $status)
    {
        try {
            DB::transaction(function () use ($registrationId, $status) {
                $this->registrationService->updateStatusRegistration($registrationId, $status);

                $registrationData = $this->registrationService->getOriginalRegistrationById($registrationId);

                $data = [
                    'group_id' => $registrationData->group_id,
                    'industry_id' => $registrationData->industry_id,
                    'start_date' => $registrationData->start_date,
                    'end_date' => $registrationData->end_date,
                    'batch_id' => $registrationData->batch_id,
                ];

                if ($status == 'accept') {
                    // tambah registration to internship data
                    $this->handleAcceptRegistration($data);
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
        $newStatus = $request->status == 'accept' ? '1' : '2';
        $registrationData = $this->registrationService->getRegistrationById($registrationId);

        if ($registrationData->status == $newStatus) {
            Toastr::addInfo('Tidak ada perubahan status.');
            return redirect()->back();
        }

        try {
            DB::transaction(function () use ($registrationId, $registrationData, $newStatus) {
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
                    $this->handleAcceptRegistration($data);
                } elseif ($newStatus == '2') {
                    // update status registration
                    $this->registrationService->updateStatusRegistration($registrationId, 'reject');

                    // delete internship
                    $internship_id = $this->internshipService->getInternshipByGroupId($registrationData->group_id)->id;
                    $this->internshipService->deleteInternship($internship_id);
                }
            });
            Toastr::addSuccess('Status berhasil diubah!');
        } catch (\Exception $e) {
            Toastr::addError('Status gagal diubah!');
        }
        return redirect()->back();
    }
}
