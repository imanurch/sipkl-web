<?php

namespace App\Http\Controllers\Student;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\GroupService;
use App\Services\AdvisorService;
use App\Services\StudentService;
use App\Services\IndustryService;
use Illuminate\Support\Facades\DB;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use App\Services\GroupMemberService;
use Illuminate\Support\Facades\Auth;
use App\Services\RegistrationService;
use App\Services\InternDocumentService;
use Flasher\Toastr\Laravel\Facade\Toastr;
use App\Services\RegistrationDocumentService;

class RegistrationStudentController extends Controller
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
        $internDocumentService;

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
        InternDocumentService $internDocumentService
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
    }

    public function index(Request $request)
    {
        $industryData = $this->industryService->getPartnerIndustryList();

        $activeBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $activeBatch != null ? $activeBatch->id : '';
        $request->session()->put('batch_id', $batch_id);

        $student_id = session('user_bio')->id;
        $registration = $this->registrationService->getRegistrationByStudentId($batch_id, $student_id);
        $step = $registration != null ? $registration->step : '1';
        $registration_id = $registration != null ? $registration->id : '';

        $industryRequestData = $this->industryService->getUnconfirmedIndustry();

        $request->session()->put('registration_id', $registration_id);

        // history
        $historyData = $this->registrationService->getAllHistoryRegistrationByStudentId($student_id);

        if ($step == '1') {
            return view('pages.student.registration', [
                'historyData' => $historyData,
                'industryData' => $industryData,
                'industryRequestData' => $industryRequestData,
                'pages' => 'registration',
            ]);
        } else {
            $routeName = "student.registration.step{$step}";
            return redirect()->route($routeName);
        }
    }

    public function history(Request $request)
    {
        $student_id = session('user_bio')->id;

        // history
        $historyData = $this->registrationService->getAllHistoryRegistrationByStudentId($student_id);
        foreach ($historyData as $dt) {
            $dt->status = $dt->status == '0' ? 'Belum Dikonfirmasi' : ($dt->status == '1' ? 'Diterima' : 'Ditolak');
        }
        return view('pages.student.registration', [
            'historyData' => $historyData,
            'pages' => 'registration',
        ]);
    }

    public function newIndustryRequest(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'address' => 'required|string',
                'email' => 'required|unique:industries,email|email',
                'phone_num' => 'required|unique:industries,phone_num|string|min:10|max:14',
                'leader_name' => 'required|string',
            ]);

            $this->industryService->addIndustry($validatedData);
            Toastr::addSuccess('Pengajuan industri berhasil disimpan!');
        } catch (\Exception $e) {
            Toastr::addError('Pengajuan industri gagal disimpan');
        }
        return redirect()->back();
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

            $student_id = session('user_bio')->id;
            $student_department = session('user_bio')->department_id;

            $studentListData = $this->studentService->getNonRegisteredInternList($batch_id, $student_department);
            $studentListData = $studentListData->reject(function ($student) use ($student_id) {
                return $student->id == $student_id;
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
            $student_id = session('user_bio')->id;
            $member_id[] = $student_id;

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

    public function step4(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'industry_id' => 'required',
                'student_id' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ]);

            // create group
            // NAMA GROUP DARIMANA?
            $groupData = [
                'name' => 'Group Testing'
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
            Toastr::addSuccess('Data registrasi berhasil disimpan!');
            return redirect()->route('student.registration');
        } catch (\Exception $e) {
            Toastr::addError('Data registrasi gagal disimpan!');
        }
    }

    public function step4View()
    {
        $registration_id =  session()->get('registration_id');
        $registrationData = $this->registrationService->getRegistrationById($registration_id);
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
            // dd('halo');
            $path_file_balasan = $validatedData['surat_balasan']->store('registration_document/surat_balasan');
            $filename = basename($path_file_balasan);

            DB::transaction(function () use ($validatedData, $filename) {
                $this->registrationDocumentService->updateRegistrationDocument($validatedData['registration_id'], 'surat balasan', $filename);

                $registration_id =  session()->get('registration_id');
                $registrationData = $this->registrationService->getRegistrationById($registration_id);

                $student_id = session('user_bio')->id;
                $surat_jalan = $this->internDocumentService->getInternDocumentByStudentId($student_id, 'surat jalan');
                $registrationData->surat_jalan = $surat_jalan != null ? $surat_jalan->url : 'Belum Tersedia';

                $this->registrationService->updateRegistrationStep($registration_id, '5');
            });
            Toastr::addSuccess('File Bukti berhasil diunggah!');
            return redirect()->route('student.registration');
        } catch (\Exception $e) {
            Toastr::addError('File Bukti gagal diunggah!');
            return redirect()->back();
        }
    }

    public function step5View()
    {
        $registration_id =  session()->get('registration_id');
        $registrationData = $this->registrationService->getRegistrationById($registration_id);
        $step = $registrationData->step;
        if ($step != '5') {
            return redirect()->back();
        }

        $student_id = session('user_bio')->id;
        $surat_jalan = $this->internDocumentService->getInternDocumentByStudentId($student_id, 'surat jalan');
        // dd($surat_jalan);
        $registrationData->surat_jalan = $surat_jalan != null ? $surat_jalan->url : 'Belum Tersedia';

        return view('pages.student.registration', [
            'registrationData' => $registrationData,
            'pages' => 'registration',
        ]);
    }

    public function downloadFile($type, $filename)
    {
        $path = ($type == 'surat_permohonan' ? storage_path('app/registration_document/surat_permohonan/' . $filename) : ($type == 'surat_balasan' ? storage_path('app/registration_document/surat_balasan/' . $filename) : $path = storage_path('app/intern_documents/surat_jalan/' . $filename)));

        if (file_exists($path)) {
            return response()->download($path);
        } else {
            Toastr::addError('File tidak ditemukan!');
            return redirect()->back();
        }
    }

    public function repeatRegistration($lastRegistrationId)
    {
        $this->registrationService->updateStatusRegistration($lastRegistrationId, 'reject');
        $this->registrationService->updateRegistrationStep($lastRegistrationId, '0');

        return redirect()->route('student.registration');
    }
}
