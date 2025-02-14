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

    public function updateStatusRegistration($id, $status)
    {
        // dd($id, $status);
        return $this->registrationRepository->updateStatusRegistration($id, $status);
    }
}
