<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\BatchService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\AdvisorService;
use Illuminate\Support\Facades\DB;
use App\Services\InternshipService;
use App\Services\MonitoringService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\SchoolProfileService;
use Flasher\Toastr\Laravel\Facade\Toastr;
use App\Services\MonitoringDocumentService;

class MonitoringAdminController extends Controller
{
    protected $monitoringService,
        $batchService,
        $internshipService,
        $monitoringDocumentService,
        $advisorService,
        $schoolProfileService;

    // Constructor Injection
    public function __construct(
        MonitoringService $monitoringService,
        BatchService $batchService,
        InternshipService $internshipService,
        MonitoringDocumentService $monitoringDocumentService,
        AdvisorService $advisorService,
        SchoolProfileService $schoolProfileService
    ) {
        $this->monitoringService = $monitoringService;
        $this->batchService = $batchService;
        $this->internshipService = $internshipService;
        $this->monitoringDocumentService = $monitoringDocumentService;
        $this->advisorService = $advisorService;
        $this->schoolProfileService = $schoolProfileService;
    }

    public function index(Request $request)
    {
        // $user_id = Auth::user()->id;
        // $advisor_id = $this->advisorService->getAdvisorIdByUserId($user_id);
        // $advisor_id = session('user_bio')->id;

        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $request->batch ?? ($currentBatch->id ?? '');

        $batchData = $this->batchService->getAllBatch('');

        // filter
        $filters = [
            'search' => $request->searchKeyword ?? '',
            'type' => $request->type ?? '',
            'batch_id' => $batch_id,
        ];

        $data = $this->monitoringService->getMonitoring($batch_id, $filters);
        // dd($data);
        $internshipListData = $this->internshipService->getAllInternshipList($batch_id);
        // dd($internshipListData);


        return view('pages.admin.monitoring', [
            'data' => $data,
            'batchData' => $batchData,
            'filters' => $filters,
            'internshipListData' => $internshipListData,
            'pages' => 'monitoring',
        ]);
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
            $this->monitoringService->addMonitoring($validatedData);
            Toastr::addSuccess('Data monitoring berhasil ditambah!');
        } catch (\Exception $e) {
            Toastr::addError('Data monitoring gagal ditambah!');
        }
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $data = $request->except(['_token', '_method']);

        // dd($request->all(),$id);

        try {
            $validatedData = $request->validate([
                'type' => 'required',
                'date' => 'required',
                'note' => 'nullable|string',
            ]);

            $lastMonitoringData = $this->monitoringService->getById($id);
            // $lastMonitoringDocumentType = $lastMonitoringData->type == 'pelepasan' ? 'surat pengantar' : ($lastMonitoringData->type == 'penarikan' ? 'surat penarikan' : 'surat tugas');
            // $lastMonitoringDocumentId = $this->monitoringDocumentService->getByMonitoringIdAndType($id, $lastMonitoringDocumentType)->id;

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


    public function generateSurat(Request $request)
    {
        // dd($request->all());
        $monitoring_data = $this->monitoringService->getById($request->monitoring_id);
        // dd($monitoring_data);
        $school_profile = $this->schoolProfileService->getSchoolProfile();

        // generate dokumen
        if ($request->documentGenerateType == 'Surat Tugas') {
            $data = [
                'school_phone_num'  => $school_profile->phone_num,
                'school_website'  => $school_profile->website,
                'school_email'  => $school_profile->email,
                'letter_num' => $request->letter_num,
                'advisor_name' => $monitoring_data->internship->advisor->name,
                'advisor_nip' => $monitoring_data->internship->advisor->nip,
                'activity' => $monitoring_data->type == 'pelepasan' ? 'Pembimbingan pertama' : ($monitoring_data->type = 'monitoring' ? 'Monitoring' : 'Pembimbingan kedua'),
                'create_date'  => date('d-m-Y'),
                'principal_name' => $school_profile->principal_name,
                'principal_nip' => $school_profile->principal_nip,
                'principal_signature'  => $school_profile->principal_signature,
                'internship_team_decree'  => $school_profile->internship_team_decree,                
            ];

            $pdf = Pdf::loadView('document_templates/surat_tugas_advisor', $data);
            $filename = 'surat_tugas_advisor' . time() . '.pdf';

            $path = storage_path('app/monitoring_documents/surat_tugas/' . $filename);
            $pdf->save($path);
        } elseif ($request->documentGenerateType == 'SPPD') {
            $data = [
                'school_phone_num'  => $school_profile->phone_num,
                'school_website'  => $school_profile->website,
                'school_email'  => $school_profile->email,
                'letter_num' => $request->letter_num,
                'advisor_name' => $monitoring_data->internship->advisor->name,
                'advisor_nip' => $monitoring_data->internship->advisor->nip,
                'industry_name' => $monitoring_data->internship->industry->name,
                'monitoring_date' => date("d/m/Y", strtotime($monitoring_data->date)),
                'academic_year' => date("Y", strtotime($monitoring_data->date)) . '/' . (date("Y", strtotime($monitoring_data->date)) + 1),
                'activity' => $monitoring_data->type == 'pelepasan' ? 'Pembimbingan pertama' : ($monitoring_data->type = 'monitoring' ? 'Monitoring' : 'Pembimbingan kedua'),
                'principal_name' => $school_profile->principal_name,
                'principal_nip' => $school_profile->principal_nip,
            ];

            $pdf = Pdf::loadView('document_templates/sppd_advisor', $data);
            $filename = 'SPPD_' . time() . '.pdf';

            $path = storage_path('app/monitoring_documents/sppd/' . $filename);
            $pdf->save($path);
        } elseif ($request->documentGenerateType == 'Surat Pengantar') {
            $intern_data = [];
            foreach ($monitoring_data->internship->group->groupMember as $member) {
                $intern_data[] = $member->student;
                $department = $member->student->department->name;
            }
            // dd($intern_data);
            $data = [
                'school_phone_num'  => $school_profile->phone_num,
                'school_website'  => $school_profile->website,
                'school_email'  => $school_profile->email,
                'letter_num' => $request->letter_num,
                'advisor_name' => $monitoring_data->internship->advisor->name,
                'advisor_phone_num' => $monitoring_data->internship->advisor->phone_num,
                'industry_name' => $monitoring_data->internship->industry->name,
                'industry_address' => $monitoring_data->internship->industry->address,
                'activity' => $monitoring_data->type == 'pelepasan' ? 'Pembimbingan pertama' : ($monitoring_data->type = 'monitoring' ? 'Monitoring' : 'Pembimbingan kedua'),
                'academic_year' => date("Y", strtotime($monitoring_data->date)) . '/' . (date("Y", strtotime($monitoring_data->date)) + 1),
                'internship_start_date' => date("d/m/Y", strtotime($monitoring_data->internship->start_date)),
                'internship_end_date' => date("d/m/Y", strtotime($monitoring_data->internship->end_date)),
                'create_date'  => date('d-m-Y'),
                'intern_group_data'  => $intern_data,
                'department'  => $department,
                'principal_name' => $school_profile->principal_name,
                'principal_nip' => $school_profile->principal_nip,
                'principal_signature'  => $school_profile->principal_signature,
            ];

            $pdf = Pdf::loadView('document_templates/surat_pelepasan_intern', $data);
            $filename = 'surat_pengantar_' . time() . '.pdf';

            $path = storage_path('app/monitoring_documents/surat_pengantar/' . $filename);
            $pdf->save($path);
        } elseif ($request->documentGenerateType == 'Surat Penarikan') {
            $intern_data = [];
            foreach ($monitoring_data->internship->group->groupMember as $member) {
                $intern_data[] = $member->student;
                $department = $member->student->department->name;
            }
            $data = [
                'school_phone_num'  => $school_profile->phone_num,
                'school_website'  => $school_profile->website,
                'school_email'  => $school_profile->email,
                'letter_num' => $request->letter_num,
                'industry_name' => $monitoring_data->internship->industry->name,
                'industry_address' => $monitoring_data->internship->industry->address,
                'activity' => $monitoring_data->type == 'pelepasan' ? 'Pembimbingan pertama' : ($monitoring_data->type = 'monitoring' ? 'Monitoring' : 'Pembimbingan kedua'),
                'academic_year' => date("Y", strtotime($monitoring_data->date)) . '/' . (date("Y", strtotime($monitoring_data->date)) + 1),
                'create_date'  => date('d-m-Y'),
                'intern_group_data'  => $intern_data,
                'department'  => $department,
                'principal_name' => $school_profile->principal_name,
                'principal_nip' => $school_profile->principal_nip,
                'principal_signature'  => $school_profile->principal_signature,
            ];

            $pdf = Pdf::loadView('document_templates/surat_penarikan_intern', $data);
            $filename = 'surat_penarikan_' . time() . '.pdf';

            $path = storage_path('app/monitoring_documents/surat_penarikan/' . $filename);
            $pdf->save($path);
        }

        // return $pdf->stream('dokumen.pdf');

        // save dokumen ke db
        $monitoringDocumentData = [
            'monitoring_id' => $request->monitoring_id,
            'type' => strtolower($request->documentGenerateType),
            'url' => $filename,
        ];

        try {
            $this->monitoringDocumentService->updateOrCreateMonitoringDocument($monitoringDocumentData);
            Toastr::addSuccess('Generate dokumen berhasil!');
        } catch (\Exception $e) {
            Toastr::addError('Generate dokumen gagal!');
        }
        return back();
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
}
