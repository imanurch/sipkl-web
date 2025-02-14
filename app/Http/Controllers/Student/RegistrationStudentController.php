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
use App\Models\RegistrationDocument;
use App\Services\GroupMemberService;
use App\Services\RegistrationDocumentService;
use App\Services\RegistrationService;

class RegistrationStudentController extends Controller
{
    protected $industryService, $studentService, $internshipService, $advisorService, $batchService, $groupService, $groupMemberService, $registrationService, $registrationDocumentService;

    // Constructor Injection
    public function __construct(IndustryService $industryService, StudentService $studentService, InternshipService $internshipService, AdvisorService $advisorService, BatchService $batchService, GroupService $groupService, GroupMemberService $groupMemberService, RegistrationService $registrationService, RegistrationDocumentService $registrationDocumentService)
    {
        $this->industryService = $industryService;
        $this->studentService = $studentService;
        $this->internshipService = $internshipService;
        $this->advisorService = $advisorService;
        $this->batchService = $batchService;
        $this->groupService = $groupService;
        $this->groupMemberService = $groupMemberService;
        $this->registrationService = $registrationService;
        $this->registrationDocumentService = $registrationDocumentService;
    }

    public function index(Request $request)
    {
        $industryData = $this->industryService->getPartnerIndustryList();

        return view('pages.student.registration', [
            'industryData' => $industryData,
        ]);
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
        // dd($validatedData);

        try {
            $this->industryService->addIndustry($validatedData);
            return redirect()->route('student.registration.step2')->with('success', 'Admin added successfully.');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            // return back()->withErrors(['error' => 'Failed to add admin.']);
        }
    }

    public function step2(Request $request)
    {
        $validatedData = $request->validate([
            'internshipLocation' => 'required',
        ]);
        $request->session()->put('internshipLocation.registration', $validatedData['internshipLocation']);
        // dd(session()->get('internshipLocation.registration'));

        $studentData = $this->studentService->getStudentList();

        return view('pages.student.registration', [
            'studentData' => $studentData,
        ]);
    }

    public function step3(Request $request)
    {
        // dd($request->teamMember);
        $validatedData = $request->validate([
            'teamMember' => 'required',
        ]);

        $industryId = session()->get('internshipLocation.registration');
        $locationInternship = $this->industryService->getIndustryById($industryId);
        $studentId = $validatedData['teamMember'];
        $teamMember = $this->studentService->getStudentById($studentId);

        // dd($studentId, $teamMember);

        // JANGAN LUPA TAMBAHIN DATA DIRI USER DI TEAM MEMBER

        return view('pages.student.registration', [
            'locationInternship' => $locationInternship,
            'teamMember' => $teamMember,
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
        // dd($validatedData['student_id']);

        // create group
        // NAMA GROUP DARIMANA?
        $groupData = [
            'name' => 'Group Testing'
        ];
        $newGroup = $this->groupService->addGroup($groupData);
        // dd($newGroup->id);
        $group_id = $newGroup->id;

        // add group member
        // JANGAN LUPA TAMBAHIN DATA DIRI USER DI TEAM MEMBER -> SEKARANG BELOM
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
        // dd($batch_id);

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
            'registration_id'=>$newRegistration->id,
            'type'=>'surat pengantar',
        ];

        $this->registrationDocumentService->addRegistrationDocument($suratPengantarData);

        // surat balasan
        $suratBalasanData = [
            'registration_id'=>$newRegistration->id,
            'type'=>'surat balasan',
        ];

        $this->registrationDocumentService->addRegistrationDocument($suratBalasanData);

        // ucapan terima kasih
        $ucapanTerimaKasihData = [
            'registration_id'=>$newRegistration->id,
            'type'=>'ucapan terima kasih',
        ];

        $this->registrationDocumentService->addRegistrationDocument($ucapanTerimaKasihData);

        // $registrationData = $this->registrationService->getRegistrationById('423');
        $registrationData = $this->registrationService->getRegistrationById($newRegistration->id);
        // dd($registrationData);


        return view('pages.student.registration', [
            'registrationData' => $registrationData,
            'teamMember' => $teamMember,
        ]);

        // $teamMember = $this->studentService->getStudentById($studentId);

        // return view('pages.student.registration', [
        // ]);
    }

    public function step5(Request $request)
    {
        // dd($request->all());
        // dd($request->file('surat_balasan')->getMimeType());

        // $validatedData = $request->validate([
        //     'registration_id' => 'required',
        //     'surat_balasan' => 'required|mimes:pdf',
        // ]);
        // // // dd($validatedData);
        // $path_file_balasan = $validatedData['surat_balasan']->store('registration_document/surat_balasan');
        // $filename = basename($path_file_balasan);
        // // dd($path_file_balasan);

        // $data = [
        //     'registration_id' => $validatedData['registration_id'],
        //     'type' => 'surat balasan',
        //     'url' => $filename,
        // ];

        // dd($data);

        $validatedData = $request->validate([
            'registration_id' => 'required',
            'surat_balasan' => 'required|mimes:pdf',
        ]);
        $path_file_balasan = $validatedData['surat_balasan']->store('registration_document/surat_balasan');
        $filename = basename($path_file_balasan);
        // dd($path_file_balasan);

        $this->registrationDocumentService->updateRegistrationDocument($validatedData['registration_id'], 'surat balasan', $filename);

        return view('pages.student.registration', [
            'filename' => $filename,
        ]);
    }

    public function downloadFile($filename)
    {
        // dd($filename);
        $path = storage_path('app/registration_document/surat_balasan/' . $filename);
        // dd($path);

        if (file_exists($path)) {
            return response()->download($path);
        } else {
            return response()->json(['message' => 'File tidak ditemukan'], 404);
        }
    }
}
