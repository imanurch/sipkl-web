<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBatchRequest;
use App\Http\Requests\UpdateBatchRequest;
use Flasher\Toastr\Laravel\Facade\Toastr;

class BatchManagementController extends Controller
{
    protected $batchService;

    // Constructor Injection
    public function __construct(BatchService $batchService)
    {
        $this->batchService = $batchService;
    }

    /**
     * Display a listing of the batches with optional search filters.
     */
    public function index(Request $request)
    {
        $searchFilters = $request->searchKeyword;
        $data = $this->batchService->getAllBatch($searchFilters);
        // $data->active_batch = $this->batchService->getBatchByStatus('active');

        return view('pages.admin.batch', [
            'data' => $data,
            'pages' => 'batchManagement',
        ]);
    }

    /**
     * Store a newly created batch in storage.
     */
    public function store(StoreBatchRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $this->batchService->createBatch($validatedData);
            Toastr::addSuccess('Data batch berhasil ditambah!');
        } catch (\Exception $e) {
            Toastr::addError('Data batch gagal ditambah!');
        }
        return redirect()->back();
    }

    /**
     * Update the specified batch in storage.
     */
    public function update(UpdateBatchRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();

            $this->batchService->updateBatch($id, $validatedData);
            Toastr::addSuccess('Data batch berhasil diubah!');
        } catch (\Exception $e) {
            Toastr::addError('Data batch gagal diubah!');
        }
        return redirect()->back();
    }

    /**
     * Set the specified batch as active.
     */
    public function setActiveBatch($id)
    {
        try {
            $this->batchService->setActiveBatch($id);
            Toastr::addSuccess('Berhasil mengatur Batch Aktif!');
        } catch (\Exception $e) {
            Toastr::addError('Gagal mengatur Batch Aktif!');
        }
        return back();
    }

    /**
     * Remove the specified batch from storage.
     */
    public function destroy($id)
    {
        try {
            $this->batchService->deleteBatch($id);
            Toastr::addSuccess('Data batch berhasil dihapus!');
        } catch (\Exception $e) {
            Toastr::addError('Data batch gagal dihapus!');
        }
        return redirect()->back();
    }
}
