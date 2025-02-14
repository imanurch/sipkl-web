<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\AdvisorService;
use App\Services\DepartmentService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AdvisorManagementController extends Controller
{
    protected $advisorService, $batchService, $departmentService;

    // Constructor Injection
    public function __construct(AdvisorService $advisorService, BatchService $batchService, DepartmentService $departmentService)
    {
        $this->advisorService = $advisorService;
        $this->batchService = $batchService;
        $this->departmentService = $departmentService;
    }

    public function index(Request $request)
    {
        // batch data
        $currentBatch = $this->batchService->getBatchByStatus('active');
        // dd($currentBatch->id);
        $batch_id = $request->batch ?? ($currentBatch->id ?? '');
        // dd($batch_id);

        // table filters
        $departmentData = $this->departmentService->getAllDepartment();
        $batchData = $this->batchService->getAllBatch('');
        $filters = [
            'batch_id' => $batch_id,
            'department' => $request->department ?? '',
            // 'department_id' => '1',
            'status' => $request->status ?? '',
            // 'status' => $request->status ? ($request->status == 'Aktif' ? 'active' : 'inactive') : '',
            // 'status' => 'inactive',
            'search' => $request->searchKeyword ?? '',
            // 'search' => 'Mica',
        ];
        // dd($filters);

        // table data
        $data = $this->advisorService->getAdvisor($filters);

        // card data
        $activeAdvisor = $this->advisorService->getAdvisorByStatusCount($batch_id, 'active');
        $inactiveAdvisor = $this->advisorService->getAdvisorByStatusCount($batch_id, 'inactive');

        // return view('pages.admin.advisor', compact('data'));
        // if (empty($filters['department']) && empty($filters['status']) && empty($filters['search'])) {
        // return redirect()->to('advisor');
        // }

        return view('pages.admin.advisor', [
            'data' => $data,
            'activeAdvisor' => $activeAdvisor,
            'inactiveAdvisor' => $inactiveAdvisor,
            'batchData' => $batchData,
            'departmentData' => $departmentData,
            'filters' => $filters,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        if ($request->check_password !== $request->password) {
            return back()->withErrors(['password' => 'Passwords do not match.']);
        }
        $data = $request->except(['_token']);
        // dd($request->all());
        $validatedData = $request->validate([
            'name' => 'required|string',
            'nip' => 'required|size:18',
            'department_id' => 'required',
            'email' => 'required|unique:advisors,email|email',
            'phone_num' => 'required|unique:advisors,phone_num|string|min:10|max:14',
            'password' => 'required|string|size:8',
        ]);
        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['department_id'] = $validatedData['department_id'] == 'K3R' ? '1' : ($validatedData['department_id'] == 'DPIB' ? '2' : ($validatedData['department_id'] == 'RPL' ? '3' : ''));
        
        // dd($validatedData);

        try {
            $this->advisorService->addAdvisor($validatedData);
            return redirect()->route('advisor')->with('success', 'advisor added successfully.');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return back()->withErrors(['error' => 'Failed to add advisor.']);
        }
    }

    // public function show($advisor_id)
    // {
    //     $data = $this->advisorService->getadvisorById($advisor_id);
    // }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        // dd($id);
        if ($request->check_password !== $request->password) {
            return back()->withErrors(['password' => 'Passwords do not match.']);
        }
        $data = $request->except(['_token', '_method']);
        // dd($request->all());
        // dd($data);
        $validatedData = $request->validate([
            'name' => 'required|string',
            'nip' => 'required|size:18',
            'department_id' => 'required',
            'email' => 'required|email|unique:advisors,email,' . $id,
            'phone_num' => 'required|string|min:10|max:14|unique:advisors,phone_num,' . $id,
            'password' => 'required|string|size:8',
        ]);
        $validatedData['password'] = Hash::make($validatedData['password']);
        // dd($validatedData);
        $validatedData['department_id'] = $validatedData['department_id'] == 'K3R' ? '1' : ($validatedData['department_id'] == 'DPIB' ? '2' : ($validatedData['department_id'] == 'RPL' ? '3' : ''));
        // dd($validatedData);
        try {
            $this->advisorService->updateAdvisor($id, $validatedData);
            return redirect()->route('advisor')->with('success', 'advisor added successfully.');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return back()->withErrors(['error' => 'Failed to add advisor.']);
        }
    }

    public function destroy($id)
    {
        // dd($id);
        try {
            $this->advisorService->deleteAdvisor($id);
            return redirect()->route('advisor')->with('success', 'advisor deleted successfully.');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete advisor.']);
        }
    }
}
