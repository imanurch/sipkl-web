<?php

namespace App\Http\Controllers\Student;

use App\Services\UserService;
use App\Services\StudentService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Flasher\Toastr\Laravel\Facade\Toastr;
use App\Http\Requests\UpdateAccountRequest;
use App\Http\Requests\UpdateStudentProfileRequest;

class AccountStudentController extends Controller
{
    protected
        $studentService,
        $userService;

    public function __construct(
        StudentService $studentService,
        UserService $userService
    ) {
        $this->studentService = $studentService;
        $this->userService = $userService;
    }

    /**
     * Display the student account page.
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        $data = $this->studentService->getStudentByUserId($user_id);

        return view('pages.student.account', [
            'data' => $data,
            'pages' => 'account',
        ]);
    }

    /**
     * Update the account credentials of the student user.
     *
     * @param UpdateAccountRequest $request
     */
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

    /**
     * Update the profile information of the student user.
     *
     * @param UpdateStudentProfileRequest $request
     */
    public function updateProfile(UpdateStudentProfileRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $this->studentService->updateStudent($request->profile_id, ['phone_num' => $validatedData['phone_num']]);
            Toastr::addSuccess('Data profil berhasil diubah!');
        } catch (\Exception $e) {
            Toastr::addError('Data profil gagal diubah!');
        }
        return redirect()->back();
    }
}
