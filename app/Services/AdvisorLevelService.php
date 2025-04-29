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

    /**
     * Retrieve all advisor levels.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllLevel()
    {
        return $this->advisorLevelRepository->getAllLevel();
    }
}
