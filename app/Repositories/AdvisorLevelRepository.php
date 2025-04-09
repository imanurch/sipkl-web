<?php

namespace App\Repositories;

use App\Models\AdvisorLevel;

class AdvisorLevelRepository
{
    public function getAllLevel()
    {
        return AdvisorLevel::get();
    }
}
