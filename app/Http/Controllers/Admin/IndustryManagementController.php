<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\IndustryService;
use App\Services\DepartmentService;
use App\Http\Controllers\Controller;

class IndustryManagementController extends Controller
{
    protected $industryService, $batchService, $departmentService;

    // Constructor Injection
    public function __construct(IndustryService $industryService, BatchService $batchService, DepartmentService $departmentService)
    {
        $this->industryService = $industryService;
        $this->batchService = $batchService;
        $this->departmentService = $departmentService;
    }

    public function index(Request $request)
    {
        // batch data
        $current_batch = $this->batchService->getBatchByStatus('active');
        if ($current_batch != null) {
            $batch_id = $current_batch->id;
        } else {
            $batch_id = '';
        }

        // table filters
        $filters = [
            'unconfirmedIndustrySearch' => $request->unconfirmedSearchKeyword ?? '',
            'partnerIndustrySearch' => $request->partnerSearchKeyword ?? '',
            'rejectedIndustrySearch' => $request->rejectedSearchKeyword ?? '',
            'status' => $request->status ?? '',
        ];

        // data table
        $unconfirmedData = $this->industryService->getUnconfirmedIndustry($filters);
        $partnerData = $this->industryService->getPartnerIndustry($filters, $batch_id);
        $rejectedData = $this->industryService->getRejectedIndustry($filters);

        // card data
        $activeIndustry = $this->industryService->getIndustryByStatusCount($batch_id, 'active');
        $inactiveIndustry = $this->industryService->getIndustryByStatusCount($batch_id, 'inactive');
        $unconfirmedIndustry = $this->industryService->getIndustryByConfirmStatusCount('unconfirmed');
        $partnerIndustry = $this->industryService->getIndustryByConfirmStatusCount('partner');
        $rejectedIndustry = $this->industryService->getIndustryByConfirmStatusCount('rejected');

        return view('pages.admin.industry', [
            'unconfirmedIndustryData' => $unconfirmedData,
            'partnerIndustryData' => $partnerData,
            'rejectedIndustryData' => $rejectedData,
            'activeIndustry' => $activeIndustry,
            'inactiveIndustry' => $inactiveIndustry,
            'unconfirmedIndustry' => $unconfirmedIndustry,
            'partnerIndustry' => $partnerIndustry,
            'rejectedIndustry' => $rejectedIndustry,
            'filters' => $filters,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->except(['_token']);
        $validatedData = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|unique:industries,email|email',
            'phone_num' => 'required|unique:industries,phone_num|string|min:10|max:14',
        ]);
        $validatedData['status'] = '1';

        try {
            $this->industryService->addIndustry($validatedData);
            return redirect()->route('industryManagement')->with('success', 'Industry added successfully.');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return back()->withErrors(['error' => 'Failed to add industry.']);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->except(['_token', '_method']);
        $validatedData = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|email|unique:industries,email,' . $id,
            'phone_num' => 'required|string|min:10|max:14|unique:industries,phone_num,' . $id,
        ]);

        try {
            $this->industryService->updateIndustry($id, $validatedData);
            return redirect()->route('industryManagement')->with('success', 'industry added successfully.');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return back()->withErrors(['error' => 'Failed to add industry.']);
        }
    }

    public function confirmStatusIndustry($industryId, $status)
    {
        $this->industryService->updateIndustryRequestStatus($industryId, $status);
        return back();
    }

    public function destroy($id)
    {
        try {
            $this->industryService->deleteIndustry($id);
            return redirect()->route('industryManagement')->with('success', 'industry deleted successfully.');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete industry.']);
        }
    }
}
