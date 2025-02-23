<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\AdvisorService;
use App\Services\DepartmentService;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;

class AdvisorManagementController extends Controller
{
    protected
        $advisorService,
        $batchService,
        $departmentService,
        $userService;

    // Constructor Injection
    public function __construct(
        AdvisorService $advisorService,
        BatchService $batchService,
        DepartmentService $departmentService,
        UserService $userService
    ) {
        $this->advisorService = $advisorService;
        $this->batchService = $batchService;
        $this->departmentService = $departmentService;
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        // batch data
        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $request->batch ?? ($currentBatch->id ?? '');

        // table filters
        $departmentData = $this->departmentService->getAllDepartment();
        $batchData = $this->batchService->getAllBatch('');
        $filters = [
            'batch_id' => $batch_id,
            'department' => $request->department ?? '',
            'status' => $request->status ?? '',
            'search' => $request->searchKeyword ?? '',
        ];

        // table data
        $data = $this->advisorService->getAdvisor($filters);

        // card data
        $activeAdvisor = $this->advisorService->getAdvisorByStatusCount($batch_id, 'active');
        $inactiveAdvisor = $this->advisorService->getAdvisorByStatusCount($batch_id, 'inactive');

        return view('pages.admin.advisor', [
            'data' => $data,
            'activeAdvisor' => $activeAdvisor,
            'inactiveAdvisor' => $inactiveAdvisor,
            'batchData' => $batchData,
            'departmentData' => $departmentData,
            'filters' => $filters,
            'pages' => 'advisorManagement',
        ]);
    }

    public function store(Request $request)
    {
        if ($request->check_password !== $request->password) {
            return back()->withErrors(['password' => 'Passwords do not match.']);
        }
        $data = $request->except(['_token']);
        $validatedData = $request->validate([
            'name' => 'required|string',
            'nip' => 'required|size:18',
            'department_id' => 'required',
            'username' => 'required|string',
            'email' => 'required|unique:users,email|email',
            'phone_num' => 'required|unique:advisors,phone_num|string|min:10|max:14',
            'password' => 'required|string|size:8',
        ]);
        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['department_id'] = $validatedData['department_id'] == 'K3R' ? '1' : ($validatedData['department_id'] == 'DPIB' ? '2' : ($validatedData['department_id'] == 'RPL' ? '3' : ''));

        $newUser = $this->userService->addUser([
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'role' => 'advisor',
        ]);
        $this->advisorService->addAdvisor([
            'user_id' => $newUser->id,
            'name' => $validatedData['name'],
            'nip' => $validatedData['nip'],
            'department_id' => $validatedData['department_id'],
            'phone_num' => $validatedData['phone_num'],
        ]);

        return back();
    }

    public function update(Request $request, $id)
    {
        $data = $request->except(['_token', '_method']);

        $validatedData = $request->validate([
            'user_id' => 'required',
            'name' => 'required|string',
            'nip' => 'required|size:18',
            'department_id' => 'required',
            'username' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $request->input('user_id'),
            'phone_num' => 'required|string|min:10|max:14|unique:advisors,phone_num,' . $id,
            'password' => 'nullable|string|size:8',
        ]);

        if (!empty($validatedData['password'])) {
            if ($request->check_password !== $request->password) {
                return back()->withErrors(['password' => 'Passwords do not match.']);
            }
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $validatedData['department_id'] = $validatedData['department_id'] == 'K3R' ? '1' : ($validatedData['department_id'] == 'DPIB' ? '2' : ($validatedData['department_id'] == 'RPL' ? '3' : ''));

        $this->advisorService->updateAdvisor($id, [
            'name' => $validatedData['name'],
            'nip' => $validatedData['nip'],
            'department_id' => $validatedData['department_id'],
            'phone_num' => $validatedData['phone_num'],
        ]);

        $updateUserData = [
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
        ];

        if (isset($validatedData['password'])) {
            $updateUserData['password'] = $validatedData['password'];
        }

        $this->userService->updateUser($validatedData['user_id'], $updateUserData);
        return back();
    }

    public function destroy($id)
    {
        $user_id = $this->advisorService->getAdvisorById($id)->user_id;
        // dd($id, $user_id);
        $this->userService->deleteUser($user_id);
        return back();
    }
}
