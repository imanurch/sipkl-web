<?php

namespace App\Services;

use App\Repositories\RegistrationRepository;
use App\Repositories\StudentRegistrationRepository;

class RegistrationService
{
    protected $registrationRepository,
    $studentRegistrationRepository;

    // Constructor Injection
    public function __construct(
        RegistrationRepository $registrationRepository,
        StudentRegistrationRepository $studentRegistrationRepository
        )
    {
        $this->registrationRepository = $registrationRepository;
        $this->studentRegistrationRepository = $studentRegistrationRepository;
    }

    public function getRegistration($filters = [])
    {
        return $this->registrationRepository->getRegistration($filters);
    }

    public function getRegistrationByStatusCount($status, $batch_id)
    {
        return $this->registrationRepository->countRegistrationByStatus($status, $batch_id);
    }

    public function addRegistration($data)
    {
        return $this->studentRegistrationRepository->createRegistration($data);
    }

    public function getRegistrationById($id)
    {
        return $this->registrationRepository->findRegistrationById($id);
    }

    public function getRegistrationByStudentId($batch_id, $student_id)
    {
        return $this->studentRegistrationRepository->getRegistrationByStudentId($batch_id, $student_id);
    }

    public function getAllHistoryRegistrationByStudentId($student_id)
    {
        return $this->studentRegistrationRepository->getAllHistoryRegistrationByStudentId($student_id);
    }

    public function updateStatusRegistration($id, $status)
    {
        return $this->registrationRepository->updateStatusRegistration($id, $status);
    }

    public function updateRegistrationStep($id, $step)
    {
        return $this->studentRegistrationRepository->updateRegistrationStep($id, $step);
    }

    public function deleteRegistration($id)
    {
        return $this->registrationRepository->deleteRegistration($id);
    }
}
