<?php

namespace App\Http\Controllers\Student;

use App\Services\IndustryService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIndustryRequest;
use Flasher\Toastr\Laravel\Facade\Toastr;

class IndustryRequestController extends Controller
{
    protected $industryService;

    // Constructor Injection
    public function __construct(IndustryService $industryService)
    {
        $this->industryService = $industryService;
    }

    /**
     * Handle a new industry request submission and store the data.
     */
    public function newIndustryRequest(StoreIndustryRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $this->industryService->addIndustry($validatedData);
            Toastr::addSuccess('Pengajuan industri berhasil disimpan!');
        } catch (\Exception $e) {
            Toastr::addError('Pengajuan industri gagal disimpan');
        }
        return redirect()->back();
    }
}
