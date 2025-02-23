<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\GroupService;
use App\Services\AdvisorService;
use App\Services\StudentService;
use App\Services\IndustryService;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use App\Services\GroupMemberService;
use Illuminate\Support\Facades\Auth;
use App\Services\RegistrationService;
use App\Services\InternDocumentService;
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

        $student_id = session('user_bio')->id;
        $registration = $this->registrationService->getRegistrationByStudentId($batch_id, $student_id);
        $step = $registration != null ? $registration->step : '1';
        $registration_id = $registration != null ? $registration->id : '';

        $industryRequestData = $this->industryService->getUnconfirmedIndustry();

        $request->session()->put('registration_id', $registration_id);

        if ($step == '1') {
            return view('pages.student.registration', [
                'industryData' => $industryData,
                'industryRequestData' => $industryRequestData,
                'pages' => 'registration',
            ]);
        } else {
            $routeName = "student.registration.step{$step}";
            return redirect()->route($routeName);
        }
    }

    public function newIndustryRequest(Request $request)
    {
        // $data = $request->except(['_token']);
        $validatedData = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|unique:industries,email|email',
            'phone_num' => 'required|unique:industries,phone_num|string|min:10|max:14',
        ]);

        $this->industryService->addIndustry($validatedData);
        return back();
    }

    public function step2(Request $request)
    {
        $validatedData = $request->validate([
            'internshipLocation' => 'required',
        ]);
        $request->session()->put('internshipLocation.registration', $validatedData['internshipLocation']);

        $activeBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $activeBatch != null ? $activeBatch->id : '';

        $student_id = session('user_bio')->id;

        $studentListData = $this->studentService->getNonRegisteredInternList($batch_id);
        $studentListData = $studentListData->reject(function ($student) use ($student_id) {
            return $student->id == $student_id;
        });

        return view('pages.student.registration', [
            'studentListData' => $studentListData,
            'pages' => 'registration',
        ]);
    }

    public function step3(Request $request)
    {
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

        return view('pages.student.registration', [
            'locationInternship' => $locationInternship,
            'teamMember' => $teamMember,
            'pages' => 'registration',
        ]);
    }

    public function step4(Request $request)
    {
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
        // surat pengantar
        $suratPengantarData = [
            'registration_id' => $newRegistration->id,
            'type' => 'surat pengantar',
        ];
        $this->registrationDocumentService->addRegistrationDocument($suratPengantarData);

        // surat balasan
        $suratBalasanData = [
            'registration_id' => $newRegistration->id,
            'type' => 'surat balasan',
        ];
        $this->registrationDocumentService->addRegistrationDocument($suratBalasanData);

        // ucapan terima kasih
        $ucapanTerimaKasihData = [
            'registration_id' => $newRegistration->id,
            'type' => 'ucapan terima kasih',
        ];
        $this->registrationDocumentService->addRegistrationDocument($ucapanTerimaKasihData);

        $registrationData = $this->registrationService->getRegistrationById($newRegistration->id);

        $this->registrationService->updateRegistrationStep($newRegistration->id, '4');

        return view('pages.student.registration', [
            'registrationData' => $registrationData,
            'teamMember' => $teamMember,
            'pages' => 'registration',
        ]);
    }

    public function step4View()
    {
        $registration_id =  session()->get('registration_id');
        $registrationData = $this->registrationService->getRegistrationById($registration_id);
        $teamMember = '';
        return view('pages.student.registration', [
            'registrationData' => $registrationData,
            'teamMember' => $teamMember,
            'pages' => 'registration',
        ]);
    }

    public function step5(Request $request)
    {
        $validatedData = $request->validate([
            'registration_id' => 'required',
            'surat_balasan' => 'required|mimes:pdf',
        ]);
        $path_file_balasan = $validatedData['surat_balasan']->store('registration_document/surat_balasan');
        $filename = basename($path_file_balasan);

        $this->registrationDocumentService->updateRegistrationDocument($validatedData['registration_id'], 'surat balasan', $filename);

        $registration_id =  session()->get('registration_id');
        $registrationData = $this->registrationService->getRegistrationById($registration_id);

        $student_id = session('user_bio')->id;
        $surat_jalan = $this->internDocumentService->getInternDocumentByStudentId($student_id, 'surat jalan');
        $registrationData->surat_jalan = $surat_jalan != null ? $surat_jalan->url : 'Belum Tersedia';

        $this->registrationService->updateRegistrationStep($registration_id, '5');

        return view('pages.student.registration', [
            'registrationData' => $registrationData,
            'pages' => 'registration',
        ]);
    }

    public function step5View()
    {
        $registration_id =  session()->get('registration_id');
        $registrationData = $this->registrationService->getRegistrationById($registration_id);

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
        if($type = 'surat_jalan'){
            $path = storage_path('app/intern_documents/surat_jalan/' . $filename);
        } else{
            $path = storage_path('app/registration_document/' . $type . '/' . $filename);
        }

        if (file_exists($path)) {
            return response()->download($path);
        } else {
            return response()->json(['message' => 'File tidak ditemukan'], 404);
        }
    }
}
