<?php

namespace App\Http\Controllers\Admin;

use App\Services\BatchService;
use App\Http\Controllers\Controller;
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
        $internData = $this->studentService->getStudentById($id);
        $logbookData = $this->logbookService->getLogbookByStudentIdAndBatch($batch_id, $id);

        return view('pages.admin.logbook_detail', [
            'internData' => $internData,
            'logbookData' => $logbookData,
            'pages' => 'logbook',
        ]);
    }
}
