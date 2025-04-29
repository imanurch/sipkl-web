<?php

namespace App\Repositories;

use App\Models\Advisor;

class SpecificAdvisorRepository
{
    /**
     * Get a list of active advisors for a specific batch.
     *
     * @param int $batch_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveAdvisorList($batch_id)
    {
        // Fetches all advisors linked to internships with the specified batch_id
        return Advisor::whereHas('internship', function ($query) use ($batch_id) {
            $query->where('batch_id', $batch_id);
        })->get();
    }

    /**
     * Find an advisor by their ID.
     *
     * @param int $id
     * @return \App\Models\Advisor|null
     */
    public function findAdvisorById($id)
    {
        // Finds an advisor using their unique ID
        return Advisor::find($id);
    }

    /**
     * Get an advisor by their user ID.
     *
     * @param int $user_id
     * @return \App\Models\Advisor|null
     */
    public function getAdvisorByUserId($user_id)
    {
        // Finds an advisor by their user ID
        return Advisor::where('user_id', $user_id)->first();
    }

    /**
     * Get an advisor by their NIP.
     *
     * @param string $advisor_nip
     * @param string $batch
     * @return \App\Models\Advisor|null
     */
    public function getAdvisorByNIP($advisor_nip, $batch)
    {
        // Finds an advisor by their NIP (unique identifier)
        return Advisor::where('nip', $advisor_nip)->first();
    }
}
