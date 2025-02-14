<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\IndustryService;
use App\Services\DepartmentService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Services\internshipService;
use App\Services\LogbookService;
use App\Services\StudentService;

class LogbookAdminController extends Controller
{
    protected $logbookService, $internshipService, $studentService, $batchService;

    // Constructor Injection
    public function __construct(LogbookService $logbookService, InternshipService $internshipService, StudentService $studentService, BatchService $batchService)
    {
        $this->logbookService = $logbookService;
        $this->internshipService = $internshipService;
        $this->studentService = $studentService;
        $this->batchService = $batchService;
    }

    public function index($batch_id, $id)
    {
        // $currentBatch = $this->batchService->getBatchByStatus('active');
        // $batch_id = $request->batch ?? ($currentBatch->id ?? '');

        $internData = $this->studentService->getStudentById($id);
        $logbookData = $this->logbookService->getLogbookByStudentIdAndBatch($batch_id, $id);
        // dd($data);
        return view('pages.admin.logbook_detail', [
            'internData' => $internData,
            'logbookData' => $logbookData,
            // // 'completeOutput' => $completeOutput,
            // // 'incompleteOutput' => $incompleteOutput,
            // 'filters' => $filters,
            // 'batchData' => $batchData,
        ]);
    }
}
