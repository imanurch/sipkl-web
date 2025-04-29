<?php

namespace App\Http\Controllers\Advisor;

use App\Services\UserService;
use App\Services\AdvisorService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Flasher\Toastr\Laravel\Facade\Toastr;
use App\Http\Requests\UpdateAccountRequest;
use App\Http\Requests\UpdateAdvisorProfileRequest;

class AccountAdvisorController extends Controller
{
    protected
        $advisorService,
        $userService;

    public function __construct(
        AdvisorService $advisorService,
        UserService $userService
    ) {
        $this->advisorService = $advisorService;
        $this->userService = $userService;
    }

    public function index()
    {
        $user_id = Auth::user()->id;
        $data = $this->advisorService->getAdvisorByUserId($user_id);

        return view('pages.advisor.account', [
            'data' => $data,
            'pages' => 'account',
        ]);
    }


    public function updateAccount(UpdateAccountRequest $request)
    {
        $request->validated();

        try {
            $this->userService->updateAccountUser($request);
            Toastr::addSuccess('Data akun berhasil diubah!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMessage = collect($e->errors())->flatten()->first();
            Toastr::addError($errorMessage);
        } catch (\Exception $e) {
            Toastr::addError('Data akun gagal diubah!');
        }
        return redirect()->back();
    }

    public function updateProfile(UpdateAdvisorProfileRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $this->advisorService->updateAdvisor($request->profile_id, ['phone_num' => $validatedData['phone_num']]);
            Toastr::addSuccess('Data profil berhasil diubah!');
        } catch (\Exception $e) {
            Toastr::addError('Data profil gagal diubah!');
        }
        return redirect()->back();
    }
}
