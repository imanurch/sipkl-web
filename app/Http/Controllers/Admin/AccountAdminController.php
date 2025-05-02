<?php

namespace App\Http\Controllers\Admin;

use App\Services\UserService;
use App\Services\AdminService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Flasher\Toastr\Laravel\Facade\Toastr;
use App\Http\Requests\UpdateAccountRequest;
use App\Http\Requests\UpdateAdminProfileRequest;

class AccountAdminController extends Controller
{
    protected
        $adminService,
        $userService;

    // Constructor Injection
    public function __construct(
        AdminService $adminService,
        UserService $userService
    ) {
        $this->adminService = $adminService;
        $this->userService = $userService;
    }

    /**
     * Display the admin account page.
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $data = $this->adminService->getAdminByUserId($user_id);

        return view('pages.admin.account', [
            'data' => $data,
            'pages' => 'account',
        ]);
    }

    /**
     * Update the account credentials of the admin user.
     *
     * @param UpdateAccountRequest $request
     */
    public function updateAccount(UpdateAccountRequest $request)
    {
        $request->validated();

        try {
            $this->userService->updateAccountUser($request);
            Toastr::addSuccess('Data akun berhasil diubah!');
        } catch (\Exception $e) {
            Toastr::addError($e->getMessage());
        }
        return redirect()->back();
    }

    /**
     * Update the profile information of the admin user.
     *
     * @param UpdateAdminProfileRequest $request
     */
    public function updateProfile(UpdateAdminProfileRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $this->adminService->updateAdmin($request->profile_id, ['phone_num' => $validatedData['phone_num']]);
            Toastr::addSuccess('Data profil berhasil diubah!');
        } catch (\Exception $e) {
            Toastr::addError('Data profil gagal diubah!');
        }
        return redirect()->back();
    }
}
