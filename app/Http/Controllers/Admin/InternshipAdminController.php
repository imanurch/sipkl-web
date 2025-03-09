<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\BatchService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\AdvisorService;
use App\Services\StudentService;
use App\Services\IndustryService;
use Illuminate\Support\Facades\DB;
use App\Services\DepartmentService;
use App\Services\internshipService;
use App\Http\Controllers\Controller;
use App\Repositories\AdvisorDocumentRepository;
use Illuminate\Support\Facades\Hash;
use Flasher\Toastr\Laravel\Facade\Toastr;
use App\Services\AdvisorDocumentService;

class InternshipAdminController extends Controller
{
    protected $internshipService, $studentService, $batchService, $advisorService, $advisorDocumentService;

    // Constructor Injection
    public function __construct(InternshipService $internshipService, StudentService $studentService, BatchService $batchService, AdvisorService $advisorService, AdvisorDocumentService $advisorDocumentService)
    {
        $this->internshipService = $internshipService;
        $this->studentService = $studentService;
        $this->batchService = $batchService;
        $this->advisorService = $advisorService;
        $this->advisorDocumentService = $advisorDocumentService;
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
            $last_advisor_id = $this->internshipService->getInternshipByInternshipId($internship_id)->advisor_id;
            // dd($last_advisor_id);

            $validatedData = $request->validate([
                'advisor_id' => 'required'
            ]);
            DB::transaction(function () use ($validatedData, $internship_id, $last_advisor_id) {
                $this->internshipService->updateInternshipAdvisor($internship_id, $validatedData['advisor_id']);
                $batch_id = $this->internshipService->getInternshipByInternshipId($internship_id)->batch_id;

                // cek apakah guru yg tergantikan masih menjadi advisor atau tidak, jika tidak maka hapus surat tugas
                $internshipByLastAdvisor = $this->internshipService->getInternshipListByAdvisor($last_advisor_id, $batch_id);
                if (count($internshipByLastAdvisor) == 0) {
                    $this->advisorDocumentService->deleteAdvisorDocumentByAdvisorIdAndBatchId($last_advisor_id, $batch_id);
                }

                // cek surat tugas advisor sesuai batch
                $surat_tugas = $this->advisorDocumentService->getAdvisorDocumentByAdvisorIdAndBatchId($validatedData['advisor_id'], $batch_id);

                if ($surat_tugas == null) {
                    // generate doc dan create data ke db advisor document
                    $data = [
                        'title' => 'Contoh Dokumen PDF',
                        'date'  => date('d-m-Y'),
                    ];

                    $pdf = Pdf::loadView('document_templates/surat_pengantar_template', $data);
                    $filename = 'surat_tugas_' . time() . '.pdf';

                    $path = storage_path('app/advisor_documents/surat_tugas/' . $filename);

                    $pdf->save($path);

                    $data = [
                        'advisor_id' => $validatedData['advisor_id'],
                        'batch_id' => $batch_id,
                        'surat_tugas' => $filename,
                    ];
                    $this->advisorDocumentService->addAdvisorDocument($data);
                }
            });

            Toastr::addSuccess('Guru Pembimbing berhasil ditetapkan');
        } catch (\Exception $e) {
            Toastr::addError('Guru Pembimbing gagal ditetapkan!');
        }
        return redirect()->back();
    }

    public function destroy($id)
    {
        $last_advisor_id = $this->internshipService->getInternshipByInternshipId($id)->advisor_id;
        $batch_id = $this->internshipService->getInternshipByInternshipId($id)->batch_id;

        try {
            DB::transaction(function () use ($id, $last_advisor_id, $batch_id) {
                $this->internshipService->deleteInternship($id);

                // cek apakah guru yg internshipnya dihapus masih menjadi advisor atau tidak, jika tidak maka hapus surat tugas
                $internshipByAdvisor = $this->internshipService->getInternshipListByAdvisor($last_advisor_id, $batch_id);
                if (count($internshipByAdvisor) == 0) {
                    $this->advisorDocumentService->deleteAdvisorDocumentByAdvisorIdAndBatchId($last_advisor_id, $batch_id);
                }
            });
            Toastr::addSuccess('Data PKL berhasil dihapus!');
        } catch (\Exception $e) {
            Toastr::addError('Data PKL gagal dihapus!');
        }
        return redirect()->back();
    }
}
