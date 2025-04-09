<?php

namespace App\Services;

use App\Repositories\AdvisorLevelRepository;

class AdvisorLevelService
{
    protected $advisorLevelRepository;

    // Constructor Injection
    public function __construct(AdvisorLevelRepository $advisorLevelRepository)
    {
        $this->advisorLevelRepository = $advisorLevelRepository;
    }

    public function getAllLevel()
    {
        return $this->advisorLevelRepository->getAllLevel();
    }
}
