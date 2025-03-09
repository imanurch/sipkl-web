<?php

namespace App\Services;

use App\Repositories\RegistrationRepository;

class RegistrationService
{
    protected $registrationRepository;

    // Constructor Injection
    public function __construct(RegistrationRepository $registrationRepository)
    {
        $this->registrationRepository = $registrationRepository;
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
        return $this->registrationRepository->createRegistration($data);
    }

    public function getRegistrationById($id)
    {
        return $this->registrationRepository->findRegistrationById($id);
    }

    public function getRegistrationByStudentId($batch_id, $student_id)
    {
        return $this->registrationRepository->getRegistrationByStudentId($batch_id, $student_id);
    }

    public function getAllHistoryRegistrationByStudentId($student_id)
    {
        return $this->registrationRepository->getAllHistoryRegistrationByStudentId($student_id);
    }

    public function updateStatusRegistration($id, $status)
    {
        // dd($id, $status);
        return $this->registrationRepository->updateStatusRegistration($id, $status);
    }

    public function updateRegistrationStep($id, $step)
    {
        // dd($id, $step);
        return $this->registrationRepository->updateRegistrationStep($id, $step);
    }

    public function deleteRegistration($id)
    {
        // dd($id);
        return $this->registrationRepository->deleteRegistration($id);
    }
}
