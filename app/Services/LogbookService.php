<?php

namespace App\Services;

use App\Helpers\DateFormatHelper;
use App\Repositories\LogbookManagementRepository;
use DateTime;
use App\Repositories\LogbookRepository;

class LogbookService
{
    protected $logbookRepository,
        $logbookManagementRepository;

    // Constructor Injection
    public function __construct(
        LogbookRepository $logbookRepository,
        LogbookManagementRepository $logbookManagementRepository
    ) {
        $this->logbookRepository = $logbookRepository;
        $this->logbookManagementRepository = $logbookManagementRepository;
    }

    public function getLogbookByStudentIdAndBatch($batch_id, $student_id)
    {
        $data = $this->logbookRepository->getLogbookByStudentIdAndBatch($batch_id, $student_id);
        foreach ($data as $dt) {
            foreach ($dt as $log) {
                $log->start_date = DateFormatHelper::dateFormat($log->start_date);
                $log->end_date = DateFormatHelper::dateFormat($log->end_date);
            }
        }
        return $data;
    }

    public function getLogbookByAdvisorCount($status, $batch_id, $advisor_id)
    {
        return $this->logbookRepository->countLogbookByAdvisorStatus($status, $batch_id, $advisor_id);
    }

    public function getLogbookByLogbookId($id)
    {
        return $this->logbookRepository->getLogbookByLogbookId($id);
    }

    public function checkIsCompleteLogbookByInternshipAndStudentId($internship_id, $student_id)
    {
        return $this->logbookRepository->checkIsCompleteLogbookByInternshipAndStudentId($internship_id, $student_id);
    }

    public function updateLogbook($id, array $data)
    {
        return $this->logbookManagementRepository->updateLogbook($id, $data);
    }

    public function getLogbookByStudentAndInternshipId($student_id, $internship_id)
    {
        $data = $this->logbookRepository->getLogbookByStudentAndInternshipId($student_id, $internship_id);
        foreach ($data as $logs) {
            foreach ($logs as $log) {
                $log->start_date = DateFormatHelper::dateFormat($log->start_date);
                $log->end_date = DateFormatHelper::dateFormat($log->end_date);
            }
        }
        return $data;
    }

    public function countLogbookByAdvisorStatus($status, $batch_id, $advisor_id)
    {
        return $this->logbookRepository->countLogbookByAdvisorStatus($status, $batch_id, $advisor_id);
    }

    public function addLogbook($newInternship, $newInternId)
    {
        $logbook_start_date = new DateTime($newInternship->start_date);
        $logbook_end_date = new DateTime($newInternship->end_date);

        while ($logbook_start_date <= $logbook_end_date) {
            $current_start = clone $logbook_start_date;

            $current_end = clone $logbook_start_date;
            $current_end->modify('+6 days');

            if ($current_end > $logbook_end_date) {
                $current_end = clone $logbook_end_date;
            }

            $logbook_data = [
                'student_id'    => $newInternId,
                'internship_id' => $newInternship->id,
                'start_date'    => $current_start->format('Y-m-d'),
                'end_date'      => $current_end->format('Y-m-d')
            ];

            $this->logbookManagementRepository->createLogbook($logbook_data);

            $logbook_start_date = clone $current_end;
            $logbook_start_date->modify('+1 day');
        }
    }
}
