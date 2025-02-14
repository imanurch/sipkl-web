<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\IndustryService;
use App\Services\DepartmentService;
use App\Http\Controllers\Controller;
use App\Models\RegistrationDocument;
use Illuminate\Support\Facades\Hash;
use App\Services\RegistrationService;

class RegistrationAdminController extends Controller
{
    protected $registrationService, $batchService;

    // Constructor Injection
    public function __construct(RegistrationService $registrationService, BatchService $batchService)
    {
        $this->registrationService = $registrationService;
        $this->batchService = $batchService;
    }

    public function index(Request $request)
    {
        // batch data
        $batchData = $this->batchService->getAllBatch('');
        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $request->batch ?? ($currentBatch->id ?? '');

        // table filters
        $filters = [
            'search' => $request->searchKeyword ?? '',
            'status' => $request->status ?? '',
            'batch_id' => $request->batch ?? $batch_id,
        ];
        // dd($filters);

        // table data
        $data = $this->registrationService->getRegistration($filters);
        // dd($data);

        // card data
        $unconfirmedRegistration = $this->registrationService->getRegistrationByStatusCount('unconfirmed', $batch_id);
        $acceptedRegistration = $this->registrationService->getRegistrationByStatusCount('accepted', $batch_id);
        $rejectedRegistration = $this->registrationService->getRegistrationByStatusCount('rejected', $batch_id);

        return view('pages.admin.registration', [
            'data' => $data,
            'unconfirmedRegistration' => $unconfirmedRegistration,
            'acceptedRegistration' => $acceptedRegistration,
            'rejectedRegistration' => $rejectedRegistration,
            'filters' => $filters,
            'batchData' => $batchData,
        ]);
    }

    public function downloadFile($filename)
    {
        $path = storage_path('app/registration_document/surat_balasan/' . $filename);
        // dd($path);

        if (file_exists($path)) {
            return response()->download($path);
        } else {
            return response()->json(['message' => 'File tidak ditemukan'], 404);
        }
    }

    public function confirmStatusRegistration($registrationId, $status)
    {
        // dd($registrationId, $status);
        $this->registrationService->updateStatusRegistration($registrationId, $status);
        return back()->withInput()->with('success', 'Operasi berhasil!');
    }
}
