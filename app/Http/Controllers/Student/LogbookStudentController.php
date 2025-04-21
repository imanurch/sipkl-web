<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\LogbookService;
use App\Services\StudentService;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Flasher\Toastr\Laravel\Facade\Toastr;
use App\Notifications\LogbookNotification;
use Illuminate\Support\Facades\Notification;

class LogbookStudentController extends Controller
{
    protected
        $batchService,
        $internshipService,
        $logbookService,
        $studentService;

    // Constructor Injection
    public function __construct(
        BatchService $batchService,
        InternshipService $internshipService,
        LogbookService $logbookService,
        StudentService $studentService
    ) {
        $this->batchService = $batchService;
        $this->internshipService = $internshipService;
        $this->logbookService = $logbookService;
        $this->studentService = $studentService;
    }

    public function index()
    {
        $user_id = Auth::user()->id;
        $student_id = session('user_bio')->id;

        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $currentBatch != null ? $currentBatch->id : null;

        $data = $this->logbookService->getLogbookByStudentIdAndBatch($batch_id, $student_id);

        return view('pages.student.logbook', [
            'data' => $data,
            'pages' => 'logbook',
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'activities' => 'required',
            ]);

            $this->logbookService->updateLogbook($id, $validatedData);
            $logbookData = $this->logbookService->getLogbookByLogbookId($id);
            if ($logbookData->internship->advisor != null) {
                if ($logbookData->internship->advisor->user->email_verified_at != null) {
                    Notification::send($logbookData->internship->advisor->user, new LogbookNotification($logbookData->student->name));
                }
            }

            Toastr::addSuccess('Logbook berhasil disimpan!');
        } catch (\Exception $e) {
            Toastr::addError('Logbook gagal disimpan!');
        }
        return redirect()->back();
    }
}
