<?php

namespace App\Http\Controllers\Admin;

use Log;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\AdminService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Flasher\Toastr\Laravel\Facade\Toastr;

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
            Toastr::addError('Password tidak konsisten!');
            return redirect()->back();
        }
        $data = $request->except(['_token']);

        try {
            $validatedData = $request->validate([
                'name' => 'required|string',
                'username' => 'required|string',
                'email' => 'required|unique:users,email|email',
                'phone_num' => 'required|unique:admins,phone_num|string|min:10|max:14',
                'password' => 'required|string|min:8|max:12',
            ]);
            $validatedData['password'] = Hash::make($validatedData['password']);
            DB::transaction(function () use (&$newUser, $validatedData) {
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
            });
            
            if ($newUser && !$newUser->hasVerifiedEmail()) {
                $newUser->sendEmailVerificationNotification();
            }
            Toastr::addSuccess('Data admin berhasil disimpan!');
        } catch (\Exception $e) {
            Toastr::addError('Data admin gagal disimpan!');
        }
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $data = $request->except(['_token', '_method']);

        try {
            $validatedData = $request->validate([
                'user_id' => 'required',
                'name' => 'required|string',
                'username' => 'required|string',
                'email' => 'required|email|unique:users,email,' . $request->input('user_id'),
                'phone_num' => 'required|string|min:10|max:14|unique:admins,phone_num,' . $id,
                'password' => 'nullable|string|min:8|max:12',
            ]);

            if (!empty($validatedData['password'])) {
                if ($request->check_password !== $request->password) {
                    Toastr::addError('Password tidak konsisten!');
                    return redirect()->back();
                }
                $validatedData['password'] = Hash::make($validatedData['password']);
            }

            DB::transaction(function () use ($validatedData, $id) {
                $this->adminService->updateAdmin($id, [
                    'name' => $validatedData['name'],
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
            });

            Toastr::addSuccess('Data admin berhasil diubah!');
        } catch (\Exception $e) {
            Toastr::addError('Data admin gagal diubah!');
        }
        return redirect()->back();
    }

    public function destroy($id)
    {
        try {
            $user_id = $this->adminService->getAdminById($id)->user_id;
            $this->userService->deleteUser($user_id);
            Toastr::addSuccess('Data admin berhasil dihapus!');
        } catch (\Exception $e) {
            Toastr::addError('Data admin gagal dihapus!');
        }
        return redirect()->back();
    }
}

try {
    DB::transaction(function () use ($validatedData) {});
    Toastr::addSuccess('Data admin berhasil dihapus!');
} catch (\Exception $e) {
    Toastr::addError('Data admin gagal dihapus!');
}
return redirect()->back();
