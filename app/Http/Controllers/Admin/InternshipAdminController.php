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
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Services\SchoolProfileService;
use App\Services\InternDocumentService;
use Illuminate\Support\Facades\Storage;
use App\Services\AdvisorDocumentService;
use Flasher\Toastr\Laravel\Facade\Toastr;

class InternshipAdminController extends Controller
{
    protected $internshipService,
        $studentService,
        $batchService,
        $advisorService,
        $schoolProfileService,
        $internDocumentService;

    // Constructor Injection
    public function __construct(
        InternshipService $internshipService,
        StudentService $studentService,
        BatchService $batchService,
        AdvisorService $advisorService,
        SchoolProfileService $schoolProfileService,
        InternDocumentService $internDocumentService
    ) {
        $this->internshipService = $internshipService;
        $this->studentService = $studentService;
        $this->batchService = $batchService;
        $this->advisorService = $advisorService;
        $this->schoolProfileService = $schoolProfileService;
        $this->internDocumentService = $internDocumentService;
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
        foreach($data as $dt){
            $dt->start_date = date('d-m-Y', strtotime($dt->start_date));
            $dt->end_date = date('d-m-Y', strtotime($dt->end_date));
            foreach($dt->internDocument as $doc){
                if($doc->type == 'surat jalan'){
                    $dt->surat_jalan = true;
                }
            }
        }
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
        $doc = $this->internDocumentService->getInternDocumentByInternshipId($id);
        foreach ($doc as $dt) {
            $filename = $dt->url;
            if ($dt->type == "surat jalan") {
                Storage::delete('intern_documents/surat_jalan/' . $filename);
            } elseif ($dt->type == "laporan akhir") {
                Storage::delete('intern_documents/laporan_akhir/' . $filename);
            }
        }

        try {
            $this->internshipService->deleteInternship($id);
            Toastr::addSuccess('Data PKL berhasil dihapus!');
        } catch (\Exception $e) {
            Toastr::addError('Data PKL gagal dihapus!');
        }
        return redirect()->back();
    }

    public function generateDocument(Request $request)
    {
        // dd($request->all());

        $internship = $this->internshipService->getInternshipByInternshipId($request->internship_id);
        $school_profile = $this->schoolProfileService->getSchoolProfile();
        // dd($request->all());

        try {
            DB::transaction(function () use ($request, $internship, $school_profile) {
                if ($request->memberId != null && $request->letterNum != null) {
                    // dd($request->all());
                    foreach ($request->memberId as $index => $id) {
                        $intern_id = $id;
                        $letter_num = $request->letterNum[$index];
                        $intern_transport = $request->transportation[$index];
                        $internData = $this->studentService->getStudentById($intern_id);

                        $data = [
                            'letter_num'  => $letter_num,
                            'intern_name'  => $internData->name,
                            'intern_nis'  => $internData->nis,
                            'internship_start_date'  => $internship->start_date,
                            'internship_end_date'  => $internship->end_date,
                            'industry_name'  => $internship->industry->name,
                            'industry_address'  => $internship->industry->address,
                            'intern_transport'  => $intern_transport,
                            'create_date' => date('d F Y'),
                            'principal_name'  => $school_profile->principal_name,
                            'principal_nip'  => $school_profile->principal_nip,
                            'principal_signature'  => $school_profile->principal_signature,

                        ];

                        $pdf = Pdf::loadView('document_templates/surat_jalan', $data);
                        $filename = 'surat_jalan_' . $internData->name . '.pdf';
                        $path = storage_path('app/intern_documents/surat_jalan/' . $filename);

                        $pdf->save($path);

                        $internDocumentData = [
                            'student_id' => $intern_id,
                            'internship_id' => $request->internship_id,
                            'type' => 'surat jalan',
                            'url' => $filename
                        ];
                        $this->internDocumentService->addInternDocument($internDocumentData);
                    }
                }
            });
            Toastr::addSuccess('Surat Jalan berhasil dibuat. Silahkan refresh halaman!');
        } catch (\Exception $e) {
            Toastr::addError('Surat Jalan gagal dibuat. Silahkan refresh halaman!');
        }
        return redirect()->back();
    }

    public function downloadFile($filename)
    {
        $path = storage_path('app/intern_documents/surat_jalan/' . $filename);

        if (file_exists($path)) {
            // return response()->download($path);
            return response()->file($path);
        } else {
            return response()->json(['message' => 'File tidak ditemukan'], 404);
        }
    }
}
