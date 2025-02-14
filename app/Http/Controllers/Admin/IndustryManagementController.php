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
        $current_id = $this->batchService->getBatchByStatus('active');
        $batch_id = $current_id->id;

        // table filters
        $filters = [
            'unconfirmedIndustrySearch' => $request->unconfirmedSearchKeyword ?? '',
            'partnerIndustrySearch' => $request->partnerSearchKeyword ?? '',
            'rejectedIndustrySearch' => $request->rejectedSearchKeyword ?? '',
            'status' => $request->status ?? '',
        ];
        // $searchFilter = $request->searchKeyword ?? '';
        // dd($filters);

        // table data
        // $data = $this->industryService->getIndustry($filters);
        $unconfirmedData = $this->industryService->getUnconfirmedIndustry($filters);
        $partnerData = $this->industryService->getPartnerIndustry($filters, $batch_id);
        $rejectedData = $this->industryService->getRejectedIndustry($filters);
        // dd($data);

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
        // dd($request->all());
        $data = $request->except(['_token']);
        // dd($request->all());
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
        // dd($request->all());
        // dd($id);
        $data = $request->except(['_token', '_method']);
        // dd($request->all());
        // dd($data);
        $validatedData = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|email|unique:industries,email,' . $id,
            'phone_num' => 'required|string|min:10|max:14|unique:industries,phone_num,' . $id,
        ]);
        // dd($validatedData);
        try {
            $this->industryService->updateIndustry($id, $validatedData);
            return redirect()->route('industryManagement')->with('success', 'industry added successfully.');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return back()->withErrors(['error' => 'Failed to add industry.']);
        }
    }

    public function destroy($id)
    {
        // dd($id);
        try {
            $this->industryService->deleteIndustry($id);
            return redirect()->route('industryManagement')->with('success', 'industry deleted successfully.');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete industry.']);
        }
    }

    // public function confirm(Request $request, $id){
    //     $status = $request->status;
    //     $this->industryService->updateIndustryRequestStatus($id, $status);
    // }
}
