<?php

namespace App\Http\Controllers\Advisor;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\BatchService;
use App\Services\AdvisorService;
use App\Services\DownloadService;
use Illuminate\Support\Facades\DB;
use App\Services\InternshipService;
use App\Services\MonitoringService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMonitoringRequest;
use App\Http\Requests\UpdateMonitoringRequest;
use Flasher\Toastr\Laravel\Facade\Toastr;
use App\Services\MonitoringDocumentService;
use Illuminate\Support\Facades\Notification;
use App\Notifications\MonitoringDocumentRequestNotification;
use App\Services\DeleteDocumentService;

class MonitoringAdvisorController extends Controller
{
    protected $monitoringService,
        $batchService,
        $internshipService,
        $monitoringDocumentService,
        $advisorService,
        $userService,
        $downloadService,
        $deleteDocumentService;

    // Constructor Injection
    public function __construct(
        MonitoringService $monitoringService,
        BatchService $batchService,
        InternshipService $internshipService,
        MonitoringDocumentService $monitoringDocumentService,
        AdvisorService $advisorService,
        UserService $userService,
        DownloadService $downloadService,
        DeleteDocumentService $deleteDocumentService
    ) {
        $this->monitoringService = $monitoringService;
        $this->batchService = $batchService;
        $this->internshipService = $internshipService;
        $this->monitoringDocumentService = $monitoringDocumentService;
        $this->advisorService = $advisorService;
        $this->userService = $userService;
        $this->downloadService = $downloadService;
        $this->deleteDocumentService = $deleteDocumentService;
    }

    /**
     * Display monitoring data and related filters.
     */
    public function index(Request $request)
    {
        $advisor_id = session('user_bio')->id;

        $batchData = $this->batchService->getAllBatch('');
        $batch_id = $this->batchService->getRelevantBatch($request->batch);

        // filter
        $filters = [
            'search' => $request->searchKeyword ?? '',
            'type' => $request->type ?? '',
            'batch_id' => $batch_id,
        ];

        $data = $this->monitoringService->getMonitoringByAdvisorIdAndBatch($advisor_id, $batch_id, $filters);
        $internshipListData = $this->internshipService->getInternshipListByAdvisor($advisor_id, $batch_id);


        return view('pages.advisor.monitoring', [
            'data' => $data,
            'batchData' => $batchData,
            'filters' => $filters,
            'internshipListData' => $internshipListData,
            'pages' => 'monitoring',
        ]);
    }

    /**
     * Download monitoring document file.
     */
    public function downloadFile($type, $filename)
    {
        return $this->downloadService->monitoringDocumentDownload($type, $filename);
    }

    /**
     * Store a newly created monitoring record.
     */
    public function store(StoreMonitoringRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $monitoringData = $this->monitoringService->addMonitoring($validatedData);

            $advisorName = $monitoringData->internship->advisor->name;
            $users = $this->userService->getVerifiedAdminUser();
            Notification::send($users, new MonitoringDocumentRequestNotification($advisorName));

            Toastr::addSuccess('Data monitoring berhasil ditambah!');
        } catch (\Exception $e) {
            Toastr::addError('Data monitoring gagal ditambah!');
        }
        return redirect()->back();
    }

    /**
     * Update an existing monitoring record.
     */
    public function update(UpdateMonitoringRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();

            $lastMonitoringData = $this->monitoringService->getById($id);

            DB::transaction(function () use ($validatedData, $id) {
                // update data monitoring
                $this->monitoringService->updateMonitoring($id, $validatedData);

                // delete previous document
                $doc = $this->monitoringDocumentService->getMonitoringDocumentByMonitoringId($id);
                foreach ($doc as $dt) {
                    $this->deleteDocumentService->deleteMonitoringDocument($dt->type, $dt->url);
                }

                $this->monitoringDocumentService->deleteMonitoringDocument($id);
            });
            Toastr::addSuccess('Data monitoring berhasil diubah!');
        } catch (\Exception $e) {
            Toastr::addError('Data monitoring gagal diubah!');
        }
        return redirect()->back();
    }

    /**
     * Remove a monitoring record and associated documents.
     */
    public function destroy($id)
    {
        try {
            $this->monitoringService->deleteMonitoring($id);
            Toastr::addSuccess('Data monitoring berhasil dihapus!');
        } catch (\Exception $e) {
            Toastr::addError('Data monitoring gagal dihapus!');
        }
        return redirect()->back();
    }
}
