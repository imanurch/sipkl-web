<?php

namespace App\Repositories;

use App\Models\SchoolProfile;

class SchoolProfileRepository
{
    public function getSchoolProfile()
    {
        return SchoolProfile::first();
    }

    public function updateSchoolProfile($data)
    {
        return SchoolProfile::where('id','1')->update($data);
    }
}