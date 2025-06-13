<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAdministrationDataRequest;
use App\Services\SchoolProfileService;
use Flasher\Toastr\Laravel\Facade\Toastr;

class AdministrationDataController extends Controller
{
    protected
        $batchService,
        $schoolProfileService;

    // Constructor Injection
    public function __construct(
        BatchService $batchService,
        SchoolProfileService $schoolProfileService
    ) {
        $this->batchService = $batchService;
        $this->schoolProfileService = $schoolProfileService;
    }

    /**
     * Display the administration data page.
     *
     * @param Request $request
     */
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

    /**
     * Update the administration data.
     *
     * @param Request $request
     */
    public function update(UpdateAdministrationDataRequest $request)
    {
        $validatedData = $request->validated();

        if (!empty($validatedData['principal_signature'])) {
            $path_principal_signature_file = $validatedData['principal_signature']->store('signatures');
            $validatedData['principal_signature'] = basename($path_principal_signature_file);
        }
        if (!empty($validatedData['school_stamp'])) {
            $path_school_stamp_file = $validatedData['school_stamp']->store('signatures');
            $validatedData['school_stamp'] = basename($path_school_stamp_file);
        }

        try {
            $this->schoolProfileService->updateSchoolProfile($validatedData);
            Toastr::addSuccess('Data Administrasi berhasil diubah!');
        } catch (\Exception $e) {
            Toastr::addError('Data Administrasi gagal diubah!');
        }
        return redirect()->back();
    }

    /**
     * Download a specific file from the signatures folder.
     *
     * @param string $filename
     */
    public function downloadFile($filename)
    {
        $path = storage_path('app/signatures/' . $filename);

        if (file_exists($path)) {
            // return response()->download($path);
            return response()->file($path);
        } else {
            Toastr::addError('File tidak ditemukan!');
            return redirect()->back();
        }
    }
}
