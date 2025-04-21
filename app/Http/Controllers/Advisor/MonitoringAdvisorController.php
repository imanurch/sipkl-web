<?php

namespace App\Http\Controllers\Advisor;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\BatchService;
use App\Services\AdvisorService;
use Illuminate\Support\Facades\DB;
use App\Services\InternshipService;
use App\Services\MonitoringService;
use App\Http\Controllers\Controller;
use Flasher\Toastr\Laravel\Facade\Toastr;
use App\Services\MonitoringDocumentService;
use Illuminate\Support\Facades\Notification;
use App\Notifications\MonitoringDocumentRequestNotification;

class MonitoringAdvisorController extends Controller
{
    protected $monitoringService,
        $batchService,
        $internshipService,
        $monitoringDocumentService,
        $advisorService,
        $userService;

    // Constructor Injection
    public function __construct(
        MonitoringService $monitoringService,
        BatchService $batchService,
        InternshipService $internshipService,
        MonitoringDocumentService $monitoringDocumentService,
        AdvisorService $advisorService,
        UserService $userService
    ) {
        $this->monitoringService = $monitoringService;
        $this->batchService = $batchService;
        $this->internshipService = $internshipService;
        $this->monitoringDocumentService = $monitoringDocumentService;
        $this->advisorService = $advisorService;
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $advisor_id = session('user_bio')->id;

        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $request->batch ?? ($currentBatch->id ?? '');

        $batchData = $this->batchService->getAllBatch('');

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

    public function downloadFile($type, $filename)
    {
        $formattedString = Str::slug($type, '_');
        $path = storage_path('app/monitoring_documents/' . $formattedString . '/' . $filename);

        if (file_exists($path)) {
            return response()->file($path);
            // return response()->download($path);
        } else {
            Toastr::addError('File tidak ditemukan!');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $data = $request->except(['_token']);

        try {
            $validatedData = $request->validate([
                'internship_id' => 'required',
                'type' => 'required',
                'date' => 'required',
                'note' => 'nullable|string',
            ]);

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

    public function update(Request $request, $id)
    {
        $data = $request->except(['_token', '_method']);

        try {
            $validatedData = $request->validate([
                'type' => 'required',
                'date' => 'required',
                'note' => 'nullable|string',
            ]);

            $lastMonitoringData = $this->monitoringService->getById($id);
            
            DB::transaction(function () use ($validatedData, $id) {
                // update data monitoring
                $this->monitoringService->updateMonitoring($id, $validatedData);

                // harusnya generate ulang document tapi sementara hapus dulu aja biar diulang dr awal generate
                // seharusnya dokumen yang lama dihapus biar ga beban memori

                $this->monitoringDocumentService->deleteMonitoringDocument($id);
            });
            Toastr::addSuccess('Data monitoring berhasil diubah!');
        } catch (\Exception $e) {
            Toastr::addError('Data monitoring gagal diubah!');
        }
        return redirect()->back();
    }

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
