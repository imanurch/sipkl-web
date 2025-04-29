<?php

namespace App\Repositories;

use App\Models\AdvisorLevel;

class AdvisorLevelRepository
{
    /**
     * Retrieve all advisor levels.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllLevel()
    {
        return AdvisorLevel::get();
    }
}
