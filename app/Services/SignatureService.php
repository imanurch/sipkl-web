<?php

namespace App\Services;

use App\Repositories\SignatureRepository;

class SignatureService
{
    protected $signatureRepository;

    // Constructor Injection
    public function __construct(SignatureRepository $signatureRepository)
    {
        $this->signatureRepository = $signatureRepository;
    }

    public function getPrincipalSignature()
    {
        return $this->signatureRepository->getPrincipalSignature();
    }
}
