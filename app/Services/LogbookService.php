<?php

namespace App\Services;

use App\Repositories\LogbookRepository;

class LogbookService
{
    protected $logbookRepository;

    // Constructor Injection
    public function __construct(LogbookRepository $logbookRepository)
    {
        $this->logbookRepository = $logbookRepository;
    }

    public function getLogbookByStudentIdAndBatch($batch_id, $student_id)
    {
        return $this->logbookRepository->getLogbookByStudentIdAndBatch($batch_id, $student_id);
    }

    public function getLogbookByAdvisorCount($status, $batch_id, $advisor_id)
    {
        return $this->logbookRepository->countLogbookByAdvisorStatus($status, $batch_id, $advisor_id);
    }

    // public function isCompleteLogbook($student_id, $batch_id)
    // {
    //     return $this->logbookRepository->checkIsCompleteLogbook($student_id, $batch_id);
    // }

    public function checkIsCompleteLogbookByInternshipAndStudentId($internship_id, $student_id)
    {
        return $this->logbookRepository->checkIsCompleteLogbookByInternshipAndStudentId($internship_id, $student_id);
    }

    public function addLogbook(array $data)
    {
        return $this->logbookRepository->createLogbook($data);
    }

    public function updateLogbook($id, array $data)
    {
        return $this->logbookRepository->updateLogbook($id, $data);
    }

    public function getLogbookByStudentAndInternshipId($student_id, $internship_id)
    {
        return $this->logbookRepository->getLogbookByStudentAndInternshipId($student_id, $internship_id);
    }

    public function countLogbookByAdvisorStatus($status, $batch_id, $advisor_id)
    {
        return $this->logbookRepository->countLogbookByAdvisorStatus($status, $batch_id, $advisor_id);
    }
}
