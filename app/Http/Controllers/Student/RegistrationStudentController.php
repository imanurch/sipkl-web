<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\BatchService;
use App\Services\StudentService;
use App\Services\IndustryService;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use App\Services\RegistrationService;
use App\Services\DownloadService;

class RegistrationStudentController extends Controller
{
    protected
        $industryService,
        $studentService,
        $internshipService,
        $batchService,
        $registrationService,
        $userService,
        $downloadService,
        $student_id;

    // Constructor Injection
    public function __construct(
        IndustryService $industryService,
        StudentService $studentService,
        InternshipService $internshipService,
        BatchService $batchService,
        RegistrationService $registrationService,
        UserService $userService,
        DownloadService $downloadService
    ) {
        $this->industryService = $industryService;
        $this->studentService = $studentService;
        $this->internshipService = $internshipService;
        $this->batchService = $batchService;
        $this->registrationService = $registrationService;
        $this->userService = $userService;
        $this->downloadService = $downloadService;
        $this->middleware(function ($request, $next) {
            $this->student_id = session('user_bio')?->id;
            return $next($request);
        });
    }

    /**
     * Display registration page.
     */
    public function index(Request $request)
    {
        $industryData = $this->industryService->getPartnerIndustryList();

        $activeBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $activeBatch != null ? $activeBatch->id : '';
        $request->session()->put('batch_id', $batch_id);

        $registration = $this->registrationService->getRegistrationByStudentId($batch_id, $this->student_id);
        $step = $registration != null ? $registration->step : '1';
        $registration_id = $registration != null ? $registration->id : '';

        $industryRequestData = $this->industryService->getUnconfirmedIndustry();

        $request->session()->put('registration_id', $registration_id);

        // history
        $historyData = $this->registrationService->getAllHistoryRegistrationByStudentId($this->student_id);

        if ($step == '1') {
            return view('pages.student.registration', [
                'historyData' => $historyData,
                'industryData' => $industryData,
                'industryRequestData' => $industryRequestData,
                'pages' => 'registration',
            ]);
        } else {
            $routeName = "student.registration.step{$step}";
            return redirect()->route($routeName);
        }
    }

    /**
     * Display registration history data.
     */
    public function history()
    {
        $historyData = $this->registrationService->getAllHistoryRegistrationByStudentId($this->student_id);
        foreach ($historyData as $dt) {
            $dt->status = match ($dt->status) {
                '0' => 'Belum Dikonfirmasi',
                '1' => 'Diterima',
                default => 'Ditolak',
            };
        }
        return view('pages.student.registration', [
            'historyData' => $historyData,
            'pages' => 'registration',
        ]);
    }

    /**
     * Download registration document file.
     */
    public function downloadFile($type, $filename)
    {
        if ($type == 'surat_permohonan' || $type == 'surat_balasan') {
            return $this->downloadService->registrationDocumentDownload($type, $filename);
        } else {
            return $this->downloadService->internDocumentDownload($type, $filename);
        }
    }

    /**
     * Repeat the registration process by resetting step of the last registration.
     */
    public function repeatRegistration($lastRegistrationId)
    {
        $this->registrationService->updateStatusRegistration($lastRegistrationId, 'reject');
        $this->registrationService->updateRegistrationStep($lastRegistrationId, '0');

        return redirect()->route('student.registration');
    }
}
