<?php

namespace App\Http\Controllers\Student;

use App\Services\BatchService;
use App\Services\LogbookService;
use App\Services\StudentService;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateLogbookRequest;
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

    /**
     * Display the logbook page.
     */
    public function index()
    {
        $student_id = session('user_bio')->id;

        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $currentBatch != null ? $currentBatch->id : null;

        $data = $this->logbookService->getLogbookByStudentIdAndBatch($batch_id, $student_id);

        return view('pages.student.logbook', [
            'data' => $data,
            'pages' => 'logbook',
        ]);
    }

     /**
     * Update the student's logbook entry and notify the advisor if applicable.
     */
    public function update(UpdateLogbookRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();
            
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
