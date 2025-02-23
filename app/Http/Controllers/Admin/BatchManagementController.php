<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\AdminService;
use App\Services\StudentService;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use App\Services\AdvisorService;
use App\Services\BatchService;
use App\Services\IndustryService;
use App\Services\RegistrationService;
use Illuminate\Support\Facades\Hash;

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

        $validatedData = $request->validate([
            'name' => 'required',
            'year' => 'required',
        ]);

        try {
            $this->batchService->createBatch($validatedData);
            return redirect()->route('batch')->with('success', 'batch added successfully.');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return back()->withErrors(['error' => 'Failed to add batch.']);
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'name' => 'required',
            'year' => 'required',
        ]);

        try {
            $this->batchService->updateBatch($id, $validatedData);
            return redirect()->route('batch')->with('success', 'batch added successfully.');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return back()->withErrors(['error' => 'Failed to add batch.']);
        }
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
            return redirect()->route('batch')->with('success', 'batch deleted successfully.');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete batch.']);
        }
    }
}
