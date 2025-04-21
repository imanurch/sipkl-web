<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\StudentService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Flasher\Toastr\Laravel\Facade\Toastr;

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

    public function index()
    {
        $user_id = Auth::user()->id;
        $data = $this->studentService->getStudentByUserId($user_id);

        return view('pages.student.account', [
            'data' => $data,
            'pages' => 'account',
        ]);
    }


    public function updateAccount(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required',
            'password' => 'required',
            'new_password' => 'required|string|size:8'
        ]);

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
            'phone_num' => 'required|string|min:10|max:14|unique:students,phone_num,' . $request->profile_id,
        ]);

        try {
            $this->studentService->updateStudent($request->profile_id, ['phone_num' => $validatedData['phone_num']]);
            Toastr::addSuccess('Data profil berhasil diubah!');
        } catch (\Exception $e) {
            Toastr::addError('Data profil gagal diubah!');
        }
        return redirect()->back();
    }
}
