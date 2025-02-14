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
}
