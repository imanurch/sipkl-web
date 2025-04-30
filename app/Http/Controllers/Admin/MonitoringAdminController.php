<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\AdvisorService;
use App\Helpers\DateFormatHelper;
use App\Services\DownloadService;
use Illuminate\Support\Facades\DB;
use App\Services\InternshipService;
use App\Services\MonitoringService;
use App\Http\Controllers\Controller;
use App\Services\SchoolProfileService;
use App\Services\DeleteDocumentService;
use App\Services\DocumentGenerateService;
use Flasher\Toastr\Laravel\Facade\Toastr;
use App\Services\MonitoringDocumentService;
use App\Http\Requests\StoreMonitoringRequest;
use App\Http\Requests\UpdateMonitoringRequest;

class MonitoringAdminController extends Controller
{
    protected $monitoringService,
        $batchService,
        $internshipService,
        $monitoringDocumentService,
        $advisorService,
        $schoolProfileService,
        $downloadService,
        $documentGenerateService,
        $deleteDocumentService;

    // Constructor Injection
    public function __construct(
        MonitoringService $monitoringService,
        BatchService $batchService,
        InternshipService $internshipService,
        MonitoringDocumentService $monitoringDocumentService,
        AdvisorService $advisorService,
        SchoolProfileService $schoolProfileService,
        DownloadService $downloadService,
        DocumentGenerateService $documentGenerateService,
        DeleteDocumentService $deleteDocumentService
    ) {
        $this->monitoringService = $monitoringService;
        $this->batchService = $batchService;
        $this->internshipService = $internshipService;
        $this->monitoringDocumentService = $monitoringDocumentService;
        $this->advisorService = $advisorService;
        $this->schoolProfileService = $schoolProfileService;
        $this->downloadService = $downloadService;
        $this->documentGenerateService = $documentGenerateService;
        $this->deleteDocumentService = $deleteDocumentService;
    }

    /**
     * Display monitoring data and related filters.
     */
    public function index(Request $request)
    {
        $batchData = $this->batchService->getAllBatch('');
        $batch_id = $this->batchService->getRelevantBatch($request->batch);

        // filter
        $filters = [
            'search' => $request->searchKeyword ?? '',
            'type' => $request->type ?? '',
            'batch_id' => $batch_id,
        ];

        $data = $this->monitoringService->getMonitoring($batch_id, $filters);
        $internshipListData = $this->internshipService->getAllInternshipList($batch_id);

        return view('pages.admin.monitoring', [
            'data' => $data,
            'batchData' => $batchData,
            'filters' => $filters,
            'internshipListData' => $internshipListData,
            'pages' => 'monitoring',
        ]);
    }

    /**
     * Store a newly created monitoring record.
     */
    public function store(StoreMonitoringRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $this->monitoringService->addMonitoring($validatedData);
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
        $doc = $this->monitoringDocumentService->getMonitoringDocumentByMonitoringId($id);
        foreach ($doc as $dt) {
            $this->deleteDocumentService->deleteMonitoringDocument($dt->type, $dt->url);
        }

        try {
            $this->monitoringService->deleteMonitoring($id);
            Toastr::addSuccess('Data monitoring berhasil dihapus!');
        } catch (\Exception $e) {
            Toastr::addError('Data monitoring gagal dihapus!');
        }
        return redirect()->back();
    }

    /**
     * Generate monitoring document based on request type.
     */
    public function generateSurat(Request $request)
    {
        $monitoring = $this->monitoringService->getById($request->monitoring_id);
        $school_profile = $this->schoolProfileService->getSchoolProfile();
        $internship = $monitoring->internship;
        $advisor = $internship->advisor;
        $industry = $internship->industry;

        $common_data = [
            'school_phone_num'  => $school_profile->phone_num,
            'school_website'  => $school_profile->website,
            'school_email'  => $school_profile->email,
            'create_date'  => DateFormatHelper::dateFormat(date('d-m-Y')),
            'letter_num' => $request->letter_num,
            'activity' => $monitoring->type == 'pelepasan'
                ? 'Pembimbingan pertama'
                : ($monitoring->type == 'monitoring' ? 'Monitoring' : 'Pembimbingan kedua'),
            'academic_year' => DateFormatHelper::academicYearFormat($monitoring->date),
            'principal_name' => $school_profile->principal_name,
            'principal_nip' => $school_profile->principal_nip,
            'principal_signature'  => $school_profile->principal_signature,
        ];

        // generate dokumen
        if ($request->documentGenerateType == 'Surat Tugas') {
            $data = array_merge([
                'advisor_name' => $advisor->name,
                'advisor_nip' => $advisor->nip,
                'internship_team_decree'  => $school_profile->internship_team_decree,
            ], $common_data);
        } elseif ($request->documentGenerateType == 'SPPD') {
            $data = [
                'advisor_name' => $advisor->name,
                'advisor_position' => $advisor->position_id,
                'advisor_level' => $advisor->level_id,
                'advisor_nip' => $advisor->nip,
                'industry_name' => $industry->name,
                'monitoring_date' => DateFormatHelper::dateFormat($monitoring->date),
                'transportation' => $request->transportation,
            ];
        } elseif ($request->documentGenerateType == 'Surat Pengantar') {
            $intern_data = [];
            foreach ($internship->group->groupMember as $member) {
                $intern_data[] = $member->student;
                $department = $member->student->department->name;
            }

            $data = array_merge([
                'advisor_name' => $advisor->name,
                'advisor_phone_num' => $advisor->phone_num,
                'industry_name' => $industry->name,
                'industry_address' => $industry->address,
                'internship_start_date' => DateFormatHelper::dateFormat($internship->start_date),
                'internship_end_date' => DateFormatHelper::dateFormat($internship->end_date),
                'intern_group_data'  => $intern_data,
                'department'  => $department
            ], $common_data);
        } elseif ($request->documentGenerateType == 'Surat Penarikan') {
            $intern_data = [];
            foreach ($internship->group->groupMember as $member) {
                $intern_data[] = $member->student;
                $department = $member->student->department->name;
            }
            $data = array_merge([
                'industry_name' => $industry->name,
                'industry_address' => $industry->address,
                'intern_group_data'  => $intern_data,
                'department'  => $department,
            ], $common_data);
        }

        $filename = $this->documentGenerateService->monitoringDocumentGenerate(strtolower($request->documentGenerateType), $data);

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

    /**
     * Download monitoring document file.
     */
    public function downloadFile($type, $filename)
    {
        return $this->downloadService->monitoringDocumentDownload($type, $filename);
    }
}
