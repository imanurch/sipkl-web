<?php

namespace App\Repositories;

use App\Models\SchoolProfile;

class SchoolProfileRepository
{
    /**
     * Get the school profile.
     *
     * @return \App\Models\SchoolProfile|null
     */
    public function getSchoolProfile()
    {
        // Fetches the first school profile record from the database
        return SchoolProfile::first();
    }

    /**
     * Update the school profile.
     *
     * @param array $data
     * @return int
     */
    public function updateSchoolProfile($data)
    {
        // Updates the school profile where the id is 1
        return SchoolProfile::where('id', '1')->update($data);
    }
}
