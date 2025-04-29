<?php

namespace App\Repositories;

use App\Models\AdvisorPosition;

class AdvisorPositionRepository
{
    /**
     * Retrieve all advisor positions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllPosition()
    {
        return AdvisorPosition::get();
    }
}
