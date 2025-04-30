<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Http\Controllers\Controller;
use App\Services\DownloadService;
use App\Services\InternshipOutputService;
use App\Services\InternshipService;

class OutputController extends Controller
{
    protected
        $internshipService,
        $batchService,
        $downloadService,
        $internshipOutputService;

    // Constructor Injection
    public function __construct(
        InternshipService $internshipService,
        BatchService $batchService,
        DownloadService $downloadService,
        InternshipOutputService $internshipOutputService
    ) {
        $this->internshipService = $internshipService;
        $this->batchService = $batchService;
        $this->downloadService = $downloadService;
        $this->internshipOutputService = $internshipOutputService;
    }

    /**
     * Display output data and related filters.
     */
    public function index(Request $request)
    {
        // batch data
        $batchData = $this->batchService->getAllBatch('');
        $batch_id = $this->batchService->getRelevantBatch($request->batch);
        
        // table filters
        $filters = [
            'search' => $request->searchKeyword ?? '',
            'batch_id' => $batch_id,
        ];

        // table data
        $data = $this->internshipService->getIntern($filters);

        // set status luaran
        $this->internshipOutputService->OutputInternshipIsCompleteBundleCheck($data);

        // card
        $allIntern = $this->internshipService->getAllIntern($batch_id);
        $completeOutputCount = 0;
        $incompleteOutputCount = 0;
        foreach ($allIntern as $dt) {
            // cek final report
            foreach ($dt->groupMember as $member) {
                if ($member->group->internship) {
                    // cek luaran lengkap
                    $status = $this->internshipOutputService->OutputInternshipIsCompleteCheck($member->group->internship->id, $dt->id);
                    if ($status == 'Lengkap') {
                        $completeOutputCount++;
                    } else {
                        $incompleteOutputCount++;
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

    /**
     * Download final report document file.
     */
    public function downloadFinalReport($filename)
    {
        return $this->downloadService->internDocumentDownload('laporan akhir', $filename);
    }
}
