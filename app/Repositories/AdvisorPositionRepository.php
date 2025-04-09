<?php

namespace App\Repositories;

use App\Models\AdvisorPosition;

class AdvisorPositionRepository
{
    public function getAllPosition()
    {
        return AdvisorPosition::get();
    }
}
