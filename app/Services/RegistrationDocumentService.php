<?php

namespace App\Services;

use App\Repositories\RegistrationDocumentRepository;

class RegistrationDocumentService
{
    protected $registrationDocumentRepository;

    // Constructor Injection
    public function __construct(RegistrationDocumentRepository $registrationDocumentRepository)
    {
        $this->registrationDocumentRepository = $registrationDocumentRepository;
    }

    public function addRegistrationDocument(array $data)
    {
        return $this->registrationDocumentRepository->createRegistrationDocument($data);
    }

    public function updateRegistrationDocument($registration_id, $type, $url)
    {
        return $this->registrationDocumentRepository->updateRegistrationDocument($registration_id, $type, $url);
    }

}
