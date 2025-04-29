<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\AdminService;
use Illuminate\Support\Facades\DB;
use App\Helpers\PasswordCheckHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
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

    /**
     * Display the admin management page with optional search filter.
     *
     * @param Request $request
     */
    public function index(Request $request)
    {
        $searchFilter =  $request->searchKeyword ?? '';

        $data = $this->adminService->getAdmin($searchFilter);

        return view('pages.admin.admin', [
            'data' => $data,
            'filters' => $searchFilter,
            'pages' => 'adminManagement',
        ]);
    }

    /**
     * Store a new admin user and admin profile.
     *
     * @param StoreAdminRequest $request
     */
    public function store(StoreAdminRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['password'] = PasswordCheckHelper::handlePassword($request->password, $request->check_password);

            DB::transaction(function () use (&$newUser, $validatedData) {
                $validatedData['role'] = 'admin';
                $newUser = $this->userService->addUser($validatedData);

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
        } catch (\Illuminate\Validation\ValidationException $e) {
            Toastr::addError($e->errors()['password'][0]);
        } catch (\Exception $e) {
            Toastr::addError('Data admin gagal disimpan!');
        }
        return redirect()->back();
    }

    /**
     * Update the admin user and admin profile.
     *
     * @param UpdateAdminRequest $request
     * @param int $id
     */
    public function update(UpdateAdminRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();

            if (!empty($validatedData['password'])) {
                $validatedData['password'] = PasswordCheckHelper::handlePassword($request->password, $request->check_password);
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            Toastr::addError($e->errors()['password'][0]);
        } catch (\Exception $e) {
            Toastr::addError('Data admin gagal diubah!');
        }
        return redirect()->back();
    }

    /**
     * Delete the admin user and admin profile.
     *
     * @param int $id
     */
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
