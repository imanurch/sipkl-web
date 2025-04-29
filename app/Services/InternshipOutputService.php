<?php

namespace App\Services;

use App\Repositories\LogbookRepository;
use App\Repositories\InternDocumentRepository;

class InternshipOutputService
{
    protected $internDocumentRepository,
    $logbookRepository;

    // Constructor Injection
    public function __construct(
        InternDocumentRepository $internDocumentRepository,
        LogbookRepository $logbookRepository)
    {
        $this->internDocumentRepository = $internDocumentRepository;
        $this->logbookRepository = $logbookRepository;
    }

    public function OutputInternshipIsCompleteCheck($internship_id, $student_id)
    {
        $isCompleteFinalReport = $this->internDocumentRepository->checkIsCompleteFinalReportByInternshipAndStudentId($internship_id, $student_id);
        
        if ($isCompleteFinalReport) {
            // cek logbook
            $isCompleteLogbook = $this->logbookRepository->checkIsCompleteLogbookByInternshipAndStudentId($internship_id, $student_id);
            $status = $isCompleteLogbook ? 'Lengkap' : 'Tidak Lengkap';
        } else {
            $status = 'Tidak Lengkap';
        }
        return $status;
    }

    public function OutputInternshipIsCompleteBundleCheck($data)
    {
        foreach ($data as $dt) {
            foreach ($dt->groupMember as $member) {
                if ($member->group->internship) {
                    $dt->status = $this->OutputInternshipIsCompleteCheck($member->group->internship->id, $dt->id);
                }
            }
        }
        
    }
}
