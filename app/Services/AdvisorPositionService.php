<?php

namespace App\Services;

use App\Repositories\AdvisorPositionRepository;

class AdvisorPositionService
{
    protected $advisorPositionRepository;

    // Constructor Injection
    public function __construct(AdvisorPositionRepository $advisorPositionRepository)
    {
        $this->advisorPositionRepository = $advisorPositionRepository;
    }

    /**
     * Retrieve all advisor positions.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPosition()
    {
        return $this->advisorPositionRepository->getAllPosition();
    }
}
