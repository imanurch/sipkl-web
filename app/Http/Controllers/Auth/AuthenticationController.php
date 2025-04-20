<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\AdminService;
use App\Services\AdvisorService;
use App\Services\StudentService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Session;
use Flasher\Toastr\Laravel\Facade\Toastr;

class AuthenticationController extends Controller
{
    protected $studentService, $advisorService, $adminService, $userService;

    // Constructor Injection
    public function __construct(
        StudentService $studentService,
        AdvisorService $advisorService,
        AdminService $adminService,
        UserService $userService
    ) {
        $this->studentService = $studentService;
        $this->advisorService = $advisorService;
        $this->adminService = $adminService;
        $this->userService = $userService;
    }

    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // dd($credentials);

        if (
            Auth::attempt(['email' => $credentials['username'], 'password' => $credentials['password']]) ||
            Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])
        ) {
            $user = Auth::user();
            if ($user->role == 'admin') {
                $request->session()->regenerate();
                $user_bio = $this->adminService->getAdminByUserId($user->id);
                Session::put('user_bio', $user_bio);
                // event(new Registered($user_bio));
                $user_data = $this->userService->getUserById($user->id);
                if ($user_data && !$user_data->hasVerifiedEmail()) {
                    $user_data->sendEmailVerificationNotification();
                }
                return redirect()->route('admin.dashboard');
            } else if ($user->role == 'advisor') {
                $request->session()->regenerate();
                $user_bio = $this->advisorService->getAdvisorByUserId($user->id);
                Session::put('user_bio', $user_bio);
                return redirect()->route('advisor.dashboard');
            } else {
                $request->session()->regenerate();
                $user_bio = $this->studentService->getStudentByUserId($user->id);
                Session::put('user_bio', $user_bio);
                return redirect()->route('student.dashboard');
            }
        }

        // if (Auth::attempt($credentials)) {
        //     $request->session()->regenerate();

        //     return redirect()->intended('dashboard');
        // }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('sipkl');
    }

    public function verificationEmail($id)
    {
        $user_data = $this->userService->getUserById($id);

        try {
            if ($user_data && !$user_data->hasVerifiedEmail()) {
                $user_data->sendEmailVerificationNotification();
            }
            Toastr::addSuccess('Email verifikasi sudah terkirim. Cek email ya!');
        } catch (\Exception $e) {
            Toastr::addError('Email verifikasi gagal terkirim');
        }
        return redirect()->back();
    }
}
