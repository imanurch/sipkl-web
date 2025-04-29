<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\BatchService;
use App\Services\GroupService;
use App\Services\AdvisorService;
use App\Services\StudentService;
use App\Services\IndustryService;
use Illuminate\Support\Facades\DB;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegistrationRequest;
use App\Services\GroupMemberService;
use App\Services\RegistrationService;
use App\Services\InternDocumentService;
use Flasher\Toastr\Laravel\Facade\Toastr;
use App\Notifications\InternshipRegistrationNotification;
use App\Services\RegistrationDocumentService;
use Illuminate\Support\Facades\Notification;

class RegistrationFormStudentController extends Controller
{
    protected
        $industryService,
        $studentService,
        $internshipService,
        $advisorService,
        $batchService,
        $groupService,
        $groupMemberService,
        $registrationService,
        $registrationDocumentService,
        $internDocumentService,
        $userService,
        $student_id,
        $registration_id;

    // Constructor Injection
    public function __construct(
        IndustryService $industryService,
        StudentService $studentService,
        InternshipService $internshipService,
        AdvisorService $advisorService,
        BatchService $batchService,
        GroupService $groupService,
        GroupMemberService $groupMemberService,
        RegistrationService $registrationService,
        RegistrationDocumentService $registrationDocumentService,
        InternDocumentService $internDocumentService,
        UserService $userService
    ) {
        $this->industryService = $industryService;
        $this->studentService = $studentService;
        $this->internshipService = $internshipService;
        $this->advisorService = $advisorService;
        $this->batchService = $batchService;
        $this->groupService = $groupService;
        $this->groupMemberService = $groupMemberService;
        $this->registrationService = $registrationService;
        $this->registrationDocumentService = $registrationDocumentService;
        $this->internDocumentService = $internDocumentService;
        $this->userService = $userService;
        $this->student_id = session('user_bio')->id;
        $this->registration_id = session()->get('registration_id');
    }

    private function notification()
    {
        $users = $this->userService->getVerifiedAdminUser();
        Notification::send($users, new InternshipRegistrationNotification());
    }

    public function step2(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'internshipLocation' => 'required',
            ]);
            $request->session()->put('internshipLocation.registration', $validatedData['internshipLocation']);

            $activeBatch = $this->batchService->getBatchByStatus('active');
            $batch_id = $activeBatch != null ? $activeBatch->id : '';

            $student_department = session('user_bio')->department_id;

            $studentListData = $this->studentService->getNonRegisteredInternList($batch_id, $student_department);
            $studentListData = $studentListData->reject(function ($student) {
                return $student->id == $this->student_id;
            });
            Toastr::addSuccess('Data lokasi berhasil disimpan!');
            return view('pages.student.registration', [
                'studentListData' => $studentListData,
                'pages' => 'registration',
            ]);
        } catch (\Exception $e) {
            Toastr::addError('Data lokasi gagal disimpan!');
            return redirect()->back();
        }
    }

    public function step3(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'teamMember' => 'required',
            ]);

            $industryId = session()->get('internshipLocation.registration');
            $locationInternship = $this->industryService->getIndustryById($industryId);

            $member_id = $validatedData['teamMember'];
            // add data user
            $member_id[] = $this->student_id;

            $teamMember = $this->studentService->getStudentById($member_id);
            Toastr::addSuccess('Data anggota berhasil disimpan!');
            return view('pages.student.registration', [
                'locationInternship' => $locationInternship,
                'teamMember' => $teamMember,
                'pages' => 'registration',
            ]);
        } catch (\Exception $e) {
            Toastr::addError('Data anggota gagal disimpan!');
            return redirect()->back();
        }
    }

    public function step4(StoreRegistrationRequest $request)
    {
        try {
            $validatedData = $request->validated();

            // create group
            $groupData = [
                'name' => 'PKL' . date('YmdHis')
            ];

            DB::transaction(function () use ($validatedData, $groupData) {
                $newGroup = $this->groupService->addGroup($groupData);
                $group_id = $newGroup->id;

                // add group member
                $teamMember = $validatedData['student_id'];
                foreach ($teamMember as $dt) {
                    $memberData = [
                        'group_id' => $group_id,
                        'student_id' => $dt
                    ];
                    $this->groupMemberService->addMember($memberData);
                }

                // batch data
                $currentBatch = $this->batchService->getBatchByStatus('active');
                $batch_id = $currentBatch->id;

                // create registration
                $registrationData = [
                    'group_id' => $group_id,
                    'industry_id' => $validatedData['industry_id'],
                    'start_date' => $validatedData['start_date'],
                    'end_date' => $validatedData['end_date'],
                    'batch_id' => $batch_id,
                ];

                $newRegistration = $this->registrationService->addRegistration($registrationData);

                // create registration document
                // surat permohonan
                $suratPermohonanData = [
                    'registration_id' => $newRegistration->id,
                    'type' => 'surat permohonan',
                ];
                $this->registrationDocumentService->addRegistrationDocument($suratPermohonanData);

                // surat balasan
                $suratBalasanData = [
                    'registration_id' => $newRegistration->id,
                    'type' => 'surat balasan',
                ];
                $this->registrationDocumentService->addRegistrationDocument($suratBalasanData);

                // update step
                $registrationData = $this->registrationService->getRegistrationById($newRegistration->id);
                $this->registrationService->updateRegistrationStep($newRegistration->id, '4');
            });

            $this->notification();

            Toastr::addSuccess('Data registrasi berhasil disimpan!');
            return redirect()->route('student.registration');
        } catch (\Exception $e) {
            Toastr::addError('Data registrasi gagal disimpan!');
        }
    }

    public function step4View()
    {
        $registrationData = $this->registrationService->getRegistrationById($this->registration_id);
        $step = $registrationData->step;
        if ($step != '4') {
            return back();
        }

        $teamMember = '';
        return view('pages.student.registration', [
            'registrationData' => $registrationData,
            'teamMember' => $teamMember,
            'pages' => 'registration',
        ]);
    }

    public function step5(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'registration_id' => 'required',
                'surat_balasan' => 'required',
            ]);

            $path_file_balasan = $validatedData['surat_balasan']->store('registration_documents/surat_balasan');
            $filename = basename($path_file_balasan);

            DB::transaction(function () use ($validatedData, $filename) {
                $this->registrationDocumentService->updateRegistrationDocument($validatedData['registration_id'], 'surat balasan', $filename);

                $registrationData = $this->registrationService->getRegistrationById($this->registration_id);

                $surat_jalan = $this->internDocumentService->getInternDocumentByStudentId($this->student_id, 'surat jalan');
                $registrationData->surat_jalan = $surat_jalan != null ? $surat_jalan->url : 'Belum Tersedia';

                $this->registrationService->updateRegistrationStep($this->registration_id, '5');
            });

            $this->notification();

            Toastr::addSuccess('File Bukti berhasil diunggah!');
            return redirect()->route('student.registration');
        } catch (\Exception $e) {
            Toastr::addError('File Bukti gagal diunggah!');
            return redirect()->back();
        }
    }

    public function step5View()
    {
        $registrationData = $this->registrationService->getRegistrationById($this->registration_id);
        $step = $registrationData->step;
        if ($step != '5') {
            return redirect()->back();
        }

        $surat_jalan = $this->internDocumentService->getInternDocumentByStudentId($this->student_id, 'surat jalan');

        $registrationData->surat_jalan = ($surat_jalan != null ? $surat_jalan->url : 'Belum Tersedia');

        return view('pages.student.registration', [
            'registrationData' => $registrationData,
            'pages' => 'registration',
        ]);
    }
}
