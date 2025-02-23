<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AdminService;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;

class AdminManagementController extends Controller
{
    protected $adminService, $userService;

    // Constructor Injection
    public function __construct(AdminService $adminService, UserService $userService)
    {
        $this->adminService = $adminService;
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        // table filters
        $searchFilter =  $request->searchKeyword ?? '';

        // table data
        $data = $this->adminService->getAdmin($searchFilter);

        return view('pages.admin.admin', [
            'data' => $data,
            'filters' => $searchFilter,
            'pages' => 'adminManagement',
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
            'username' => 'required|string',
            'email' => 'required|unique:users,email|email',
            'phone_num' => 'required|unique:admins,phone_num|string|min:10|max:14',
            'password' => 'required|string|size:8',
        ]);
        $validatedData['password'] = Hash::make($validatedData['password']);

        $newUser = $this->userService->addUser([
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'role' => 'admin',
        ]);
        $this->adminService->addAdmin([
            'user_id' => $newUser->id,
            'name' => $validatedData['name'],
            'phone_num' => $validatedData['phone_num'],
        ]);

        return redirect()->route('admin.adminManagement')->with('success', 'Admin added successfully.');
        // try {
        // } catch (\Exception $e) {
        //     // \Log::error($e->getMessage());
        //     return back()->withErrors(['error' => 'Failed to add admin.']);
        // }
    }

    public function update(Request $request, $id)
    {
        $data = $request->except(['_token', '_method']);
        $validatedData = $request->validate([
            'user_id' => 'required',
            'name' => 'required|string',
            'username' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $request->input('user_id'),
            'phone_num' => 'required|string|min:10|max:14|unique:admins,phone_num,' . $id,
            'password' => 'nullable|string|size:8',
        ]);

        if (!empty($validatedData['password'])) {
            if ($request->check_password !== $request->password) {
                return back()->withErrors(['password' => 'Passwords do not match.']);
            }
            $validatedData['password'] = Hash::make($validatedData['password']);
        }
        
        try {
            $this->adminService->updateAdmin($id, [
                'name' => $validatedData['name'],
                'phone_num' => $validatedData['phone_num'],
            ]);

            $updateUserData = [
                'username' => $validatedData['username'],
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
            ];
            if (isset($validatedData['password'])) {
                $updateUserData['password'] = $validatedData['password'];
            }
            $this->userService->updateUser($validatedData['user_id'],$updateUserData);
            
            return redirect()->route('admin.adminManagement')->with('success', 'Admin added successfully.');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return back()->withErrors(['error' => 'Failed to add admin.']);
        }
    }

    public function destroy($id)
    {
        try {
            $user_id = $this->adminService->getAdminById($id)->user_id;
            $this->userService->deleteUser($user_id);
            return redirect()->route('admin.adminManagement')->with('success', 'Admin deleted successfully.');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete admin.']);
        }
    }
}
