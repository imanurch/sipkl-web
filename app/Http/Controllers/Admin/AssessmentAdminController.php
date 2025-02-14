<?php

namespace App\Http\Controllers\Admin;

use Log;
use Illuminate\Http\Request;
use App\Services\AdminService;
use App\Services\AssessmentService;
use App\Http\Controllers\Controller;
use App\Services\BatchService;
use Illuminate\Support\Facades\Hash;

class AssessmentAdminController extends Controller
{
    protected $assessmentService, $batchService;

    // Constructor Injection
    public function __construct(AssessmentService $assessmentService, BatchService $batchService)
    {
        $this->assessmentService = $assessmentService;
        $this->batchService = $batchService;
    }

    public function index(Request $request)
    {
        // batch data
        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $request->batch ?? ($currentBatch->id ?? '');

        // table filters
        $batchData = $this->batchService->getAllBatch('');
        $filters = [
            'search' => $request->searchKeyword ?? '',
            'batch_id' => $batch_id,
        ];

        // table data
        $data = $this->assessmentService->getAssessment($filters);

        return view('pages.admin.assessment', [
            'data' => $data,
            'batchData' => $batchData,
            'filters' => $filters,
        ]);
    }
    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, $id)
    // {
    //     // dd($request->all());
    //     // dd($id);
    //     if ($request->check_password !== $request->password) {
    //         return back()->withErrors(['password' => 'Passwords do not match.']);
    //     }
    //     $data = $request->except(['_token', '_method']);
    //     // dd($request->all());
    //     // dd($data);
    //     $validatedData = $request->validate([
    //         'username' => 'required|string',
    //         'email' => 'required|email|unique:admins,email,' . $id,
    //         'phone_num' => 'required|string|min:10|max:14|unique:admins,phone_num,' . $id,
    //         'password' => 'required|string|size:8',
    //     ]);
    //     // dd($validatedData);
    //     $validatedData['password'] = Hash::make($validatedData['password']);
    //     // dd($validatedData);
    //     try {
    //         $this->adminService->updateAdmin($id, $validatedData);
    //         return redirect()->route('admin')->with('success', 'Admin added successfully.');
    //     } catch (\Exception $e) {
    //         // \Log::error($e->getMessage());
    //         return back()->withErrors(['error' => 'Failed to add admin.']);
    //     }
    // }
}
