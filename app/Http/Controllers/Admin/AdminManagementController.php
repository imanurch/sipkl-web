<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AdminService;
use Illuminate\Support\Facades\Hash;
use Log;

class AdminManagementController extends Controller
{
    protected $adminService;

    // Constructor Injection
    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
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
            'username' => 'required|string',
            'email' => 'required|unique:admins,email|email',
            'phone_num' => 'required|unique:admins,phone_num|string|min:10|max:14',
            'password' => 'required|string|size:8',
        ]);
        $validatedData['password'] = Hash::make($validatedData['password']);

        try {
            $this->adminService->addAdmin($validatedData);
            return redirect()->route('admin')->with('success', 'Admin added successfully.');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return back()->withErrors(['error' => 'Failed to add admin.']);
        }
    }

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
            'username' => 'required|string',
            'email' => 'required|email|unique:admins,email,' . $id,
            'phone_num' => 'required|string|min:10|max:14|unique:admins,phone_num,' . $id,
            'password' => 'required|string|size:8',
        ]);
        // dd($validatedData);
        $validatedData['password'] = Hash::make($validatedData['password']);
        // dd($validatedData);
        try {
            $this->adminService->updateAdmin($id, $validatedData);
            return redirect()->route('admin')->with('success', 'Admin added successfully.');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return back()->withErrors(['error' => 'Failed to add admin.']);
        }
    }

    public function destroy($id)
    {
        // dd($id);
        try {
            $this->adminService->deleteAdmin($id);
            return redirect()->route('admin')->with('success', 'Admin deleted successfully.');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete admin.']);
        }
    }
}
