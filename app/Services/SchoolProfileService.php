<?php

namespace App\Services;

use App\Repositories\SchoolProfileRepository;

class SchoolProfileService
{
    protected $schoolProfileRepository;

    // Constructor Injection
    public function __construct(SchoolProfileRepository $schoolProfileRepository)
    {
        $this->schoolProfileRepository = $schoolProfileRepository;
    }

    public function getSchoolProfile()
    {
        return $this->schoolProfileRepository->getSchoolProfile();
    }

    public function updateSchoolProfile($data)
    {
        return $this->schoolProfileRepository->updateSchoolProfile($data);
    }
}
