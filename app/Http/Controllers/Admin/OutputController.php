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

class OutputController extends Controller
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

    public function index(Request $request)
    {
        // batch data
        $batchData = $this->batchService->getAllBatch('');
        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $request->batch ?? ($currentBatch->id ?? '');
        // // dd($batch_id);

        // table filters
        $filters = [
            'search' => $request->searchKeyword ?? '',
            'batch_id' => $request->batch ?? $batch_id,
        ];
        // dd($filters);

        // // table data
        $data = $this->internshipService->getIntern($filters);
        // $isCompleteLogbook = $this->logbookService->isCompleteLogbook($filters);
        // $isSubmittedReport = $this->internshipService->getInternship($filters);
        // $data = $this->internshipService->getIntern($filters);
        // // dd($data);
        // $intern = $this->internshipService->getInternCount($batch_id);

        return view('pages.admin.output', [
            'data' => $data,
            // 'completeOutput' => $completeOutput,
            // 'incompleteOutput' => $incompleteOutput,
            'filters' => $filters,
            'batchData' => $batchData,
        ]);
    }
}
