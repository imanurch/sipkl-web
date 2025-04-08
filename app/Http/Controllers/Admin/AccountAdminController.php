<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\AdminService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Flasher\Toastr\Laravel\Facade\Toastr;

class AccountAdminController extends Controller
{
    protected
        $adminService,
        $userService;

    public function __construct(
        AdminService $adminService,
        UserService $userService
    ) {
        $this->adminService = $adminService;
        $this->userService = $userService;
    }

    public function index()
    {
        $user_id = Auth::user()->id;
        $data = $this->adminService->getAdminByUserId($user_id);
        // dd($data);

        return view('pages.admin.account', [
            'data' => $data,
            'pages' => 'account',
        ]);
    }


    public function updateAccount(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'user_id' => 'required',
            'password' => 'required',
            'new_password' => 'required|string|size:8'
        ]);
        // dd($validatedData);

        $last_password = $this->userService->getUserById($request->user_id)->password;
        if (Hash::check($validatedData['password'],$last_password) == false) {
            Toastr::addError('Password Anda salah!');
            return redirect()->back();
        }

        if (!empty($validatedData['new_password'])) {
            if ($request->check_password !== $request->new_password) {
                Toastr::addError('Password baru tidak konsisten!');
                return redirect()->back();
            }
            $validatedData['new_password'] = Hash::make($validatedData['new_password']);
        }

        // dd($validatedData);
        try {
            $this->userService->updateUser($request->user_id, ['password' => $validatedData['new_password']]);
            Toastr::addSuccess('Data akun berhasil diubah!');
        } catch (\Exception $e) {
            Toastr::addError('Data akun gagal diubah!');
        }
        return redirect()->back();
    }

    public function updateProfile(Request $request)
    {
        $validatedData = $request->validate([
            'profile_id' => 'required',
            'phone_num' => 'required|string|min:10|max:14|unique:advisors,phone_num,' . $request->profile_id,
        ]);

        // dd($request->all());

        try {
            $this->adminService->updateAdmin($request->profile_id, ['phone_num' => $validatedData['phone_num']]);
            Toastr::addSuccess('Data profil berhasil diubah!');
        } catch (\Exception $e) {
            Toastr::addError('Data profil gagal diubah!');
        }
        return redirect()->back();
    }
}
