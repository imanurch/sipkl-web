<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\AdvisorService;
use App\Services\IndustryService;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use App\Services\RegistrationService;
use App\Services\SchoolProfileService;
use Flasher\Toastr\Laravel\Facade\Toastr;

class AdministrationDataController extends Controller
{
    protected
        $internshipService,
        $advisorService,
        $industryService,
        $registrationService,
        $batchService,
        $schoolProfileService;

    // Constructor Injection
    public function __construct(
        InternshipService $internshipService,
        AdvisorService $advisorService,
        IndustryService $industryService,
        RegistrationService $registrationService,
        BatchService $batchService,
        SchoolProfileService $schoolProfileService
    ) {
        $this->internshipService = $internshipService;
        $this->advisorService = $advisorService;
        $this->industryService = $industryService;
        $this->registrationService = $registrationService;
        $this->batchService = $batchService;
        $this->schoolProfileService = $schoolProfileService;
    }

    public function index(Request $request)
    {
        $batchData = $this->batchService->getAllBatch('');
        $schoolProfile = $this->schoolProfileService->getSchoolProfile();

        return view('pages.admin.administration_data', [
            'batchData' => $batchData,
            'schoolProfile' => $schoolProfile,
            'pages' => 'administrationData',
        ]);
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'email' => 'required|string',
            'phone_num' => 'required|string',
            'website' => 'required|string',
            'principal_name' => 'required|string',
            'principal_nip' => 'required|string',
            'principal_signature' => 'nullable|file',
            'school_stamp' => 'nullable|file',
            'internship_team_decree' => 'nullable|string',
        ]);

        // dd($validatedData);

        if (!empty($validatedData['principal_signature'])) {
            $path_principal_signature_file = $validatedData['principal_signature']->store('public/signatures');
            $validatedData['principal_signature'] = basename($path_principal_signature_file);
        }
        if (!empty($validatedData['school_stamp'])) {
            $path_school_stamp_file = $validatedData['school_stamp']->store('public/signatures');
            $validatedData['school_stamp'] = basename($path_school_stamp_file);
        }

        // dd($validatedData);

        try {
            $this->schoolProfileService->updateSchoolProfile($validatedData);
            Toastr::addSuccess('Data Administrasi berhasil diubah!');
        } catch (\Exception $e) {
            Toastr::addError('Data Administrasi gagal diubah!');
        }
        return redirect()->back();
    }

    public function downloadFile($filename){
        // dd($filename);
        $path = public_path('storage/signatures/' . $filename);
        // dd($path);

        if (file_exists($path)) {
            // return response()->download($path);
            return response()->file($path);
        } else {
            Toastr::addError('File tidak ditemukan!');
            return redirect()->back();
        }
    }

    // public function advisorDocumentSearch(Request $request)
    // {
    //     $batchData = $this->batchService->getAllBatch('');

    //     $validatedData = $request->validate([
    //         'advisor_nip' => 'required',
    //         'batch' => 'required'
    //     ]);
    //     $advisorData = $this->advisorService->getAdvisorByNIP($request->advisor_nip, $request->batch);
    //     return view('pages.admin.administration_data', [
    //         'batchData' => $batchData,
    //         'advisorData' => $advisorData,
    //         'pages' => 'administrationData',
    //     ]);
    // }
}
