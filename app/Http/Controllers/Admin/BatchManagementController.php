<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\AdminService;
use App\Services\BatchService;
use App\Services\AdvisorService;
use App\Services\StudentService;
use App\Services\IndustryService;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Services\RegistrationService;
use Flasher\Toastr\Laravel\Facade\Toastr;

class BatchManagementController extends Controller
{
    protected $internshipService, $advisorService, $industryService, $registrationService, $batchService;

    // Constructor Injection
    public function __construct(InternshipService $internshipService, AdvisorService $advisorService, IndustryService $industryService, RegistrationService $registrationService, BatchService $batchService)
    {
        $this->internshipService = $internshipService;
        $this->advisorService = $advisorService;
        $this->industryService = $industryService;
        $this->registrationService = $registrationService;
        $this->batchService = $batchService;
    }

    public function index(Request $request)
    {
        $searchFilters = $request->searchKeyword;
        $data = $this->batchService->getAllBatch($searchFilters);
        $data->active_batch = $this->batchService->getBatchByStatus('active');

        return view('pages.admin.batch', [
            'data' => $data,
            'pages' => 'batchManagement',
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'year' => 'required',
            ]);

            $this->batchService->createBatch($validatedData);
            Toastr::addSuccess('Data batch berhasil ditambah!');
        } catch (\Exception $e) {
            Toastr::addError('Data batch gagal ditambah!');
        }
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'year' => 'required',
            ]);

            $this->batchService->updateBatch($id, $validatedData);
            Toastr::addSuccess('Data batch berhasil diubah!');
        } catch (\Exception $e) {
            Toastr::addError('Data batch gagal diubah!');
        }
        return redirect()->back();
    }

    public function setActiveBatch($id)
    {
        $this->batchService->setActiveBatch($id);
        return back();
    }

    public function destroy($id)
    {
        try {
            $this->batchService->deleteBatch($id);
            Toastr::addSuccess('Data batch berhasil dihapus!');
        } catch (\Exception $e) {
            Toastr::addError('Data batch gagal dihapus!');
        }
        return redirect()->back();
    }
}
