<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\IndustryService;
use App\Services\DepartmentService;
use App\Http\Controllers\Controller;
use App\Services\InternDocumentService;
use Illuminate\Support\Facades\Hash;
use App\Services\internshipService;
use App\Services\LogbookService;
use App\Services\StudentService;

class OutputController extends Controller
{
    protected $logbookService,
        $internshipService,
        $studentService,
        $batchService,
        $internDocumentService;

    // Constructor Injection
    public function __construct(
        LogbookService $logbookService,
        InternshipService $internshipService,
        StudentService $studentService,
        BatchService $batchService,
        InternDocumentService $internDocumentService
    ) {
        $this->logbookService = $logbookService;
        $this->internshipService = $internshipService;
        $this->studentService = $studentService;
        $this->batchService = $batchService;
        $this->internDocumentService = $internDocumentService;
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
        // dd($data);
        // $intern = $this->internshipService->getInternCount($batch_id);

        // card
        $completeOutputCount = 0;
        $incompleteOutputCount = 0;
        foreach ($data as $dt) {
            // cek final report
            foreach ($dt->groupMember as $member) {
                if ($member->group->internship) {
                    // dd($dt->id);
                    // dd($member->group);                
                    $isCompleteFinalReport = $this->internDocumentService->checkIsCompleteFinalReportByInternshipAndStudentId($member->group->internship->id, $dt->id);
                    // dd($isCompleteFinalReport);
                    if ($isCompleteFinalReport == true) {
                        // cek logbook
                        $isCompleteLogbook = $this->logbookService->checkIsCompleteLogbookByInternshipAndStudentId($member->group->internship->id, $dt->id);
                        if ($isCompleteLogbook == true) {
                            $completeOutputCount += 1;
                        } else {
                            $incompleteOutputCount += 1;
                        }
                    } else {
                        $incompleteOutputCount += 1;
                    }
                }
            }
        }

        return view('pages.admin.output', [
            'data' => $data,
            'completeOutputCount' => $completeOutputCount,
            'incompleteOutputCount' => $incompleteOutputCount,
            'filters' => $filters,
            'batchData' => $batchData,
            'pages' => 'output',
        ]);
    }

    public function downloadFinalReport($filename)
    {

        $path = storage_path('app/intern_documents/laporan_akhir/' . $filename);
        if (file_exists($path)) {
            return response()->download($path);
        } else {
            return response()->json(['message' => 'File tidak ditemukan'], 404);
        }
    }
}
