<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\AdvisorService;
use App\Services\StudentService;
use App\Services\IndustryService;
use App\Services\DepartmentService;
use App\Services\internshipService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Flasher\Toastr\Laravel\Facade\Toastr;

class InternshipAdminController extends Controller
{
    protected $internshipService, $studentService, $batchService, $advisorService;

    // Constructor Injection
    public function __construct(InternshipService $internshipService, StudentService $studentService, BatchService $batchService, AdvisorService $advisorService)
    {
        $this->internshipService = $internshipService;
        $this->studentService = $studentService;
        $this->batchService = $batchService;
        $this->advisorService = $advisorService;
    }

    public function index(Request $request)
    {
        // batch data
        $batchData = $this->batchService->getAllBatch('');
        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $request->batch ?? ($currentBatch->id ?? '');

        // dd($batch_id);
        // table filters
        $filters = [
            'search' => $request->searchKeyword ?? '',
            'batch_id' => $request->batch ?? $batch_id,
        ];
        // dd($filters);

        // table data
        // $data = $this->internshipService->getInternship($filters);
        $data = $this->internshipService->getInternship($filters);
        // $data = $this->internshipService->getIntern($filters);
        // dd($data);
        $intern = $this->internshipService->getInternCount($batch_id);
        $advisorListData = $this->advisorService->getAdvisorList();

        return view('pages.admin.intern', [
            'data' => $data,
            'intern' => $intern,
            'filters' => $filters,
            'batchData' => $batchData,
            'advisorListData' => $advisorListData,
            'pages' => 'intern',
        ]);
    }

    public function updateAdvisor(Request $request, $internship_id)
    {
        try {
            $validatedData = $request->validate([
                'advisor_id' => 'required'
            ]);
            $this->internshipService->updateInternshipAdvisor($internship_id, $validatedData['advisor_id']);

            Toastr::addSuccess('Guru Pembimbing berhasil ditetapkan');
        } catch (\Exception $e) {
            Toastr::addError('Guru Pembimbing gagal ditetapkan!');
        }
        return redirect()->back();
    }

    public function destroy($id)
    {
        try {
            $this->internshipService->deleteInternship($id);
            Toastr::addSuccess('Data PKL berhasil dihapus!');
        } catch (\Exception $e) {
            Toastr::addError('Data PKL gagal dihapus!');
        }
        return redirect()->back();
    }
}
