<?php

namespace App\Http\Controllers\Student;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\BatchService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\LogbookService;
use App\Services\StudentService;
use App\Services\InternshipService;
use App\Services\MonitoringService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Flasher\Toastr\Laravel\Facade\Toastr;
use App\Services\MonitoringDocumentService;

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
        // $student_id = $this->studentService->getStudentIdByUserId($user_id);
        $student_id = session('user_bio')->id;

        $currentBatch = $this->batchService->getBatchByStatus('active');
        
        // $studentData = $this->studentService->getStudentById($studentId);
        $data = $this->logbookService->getLogbookByStudentIdAndBatch($currentBatch->id, $student_id);
        // $internshipData = $this->internshipService->getInternshipByInternshipId($internshipId);
        // $studentData->industry = $internshipData->industry->name;
        // dd($data);

        return view('pages.student.logbook', [
            // 'studentData' => $studentData,
            'data' => $data,
            // 'internshipListData' => $internshipListData,
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
            Toastr::addSuccess('Logbook berhasil disimpan!');
        } catch (\Exception $e) {
            Toastr::addError('Logbook gagal disimpan!');
        }
        return redirect()->back();
    }
}
