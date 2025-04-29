<?php

namespace App\Repositories;

use App\Models\Industry;

class IndustryRepository
{
    /**
     * Count industries based on internship status for a specific batch.
     *
     * @param  int     $batch_id
     * @param  string  $status ('active' or 'inactive')
     * @return int
     */
    public function countIndustryByStatus($batch_id, $status)
    {
        if ($status == 'active') {
            // Count industries linked to internships in the given batch
            return Industry::whereHas('internship', function ($query) use ($batch_id) {
                $query->where('batch_id', $batch_id);
            })->count();
        } elseif ($status == 'inactive') {
            // Count industries not linked to any internships in the given batch
            return Industry::where('status', '1')->whereDoesntHave('internship', function ($query) use ($batch_id) {
                $query->where('batch_id', $batch_id);
            })->count();
        }
    }

    /**
     * Count industries by confirmation status.
     *
     * @param  string  $confirmStatus ('unconfirmed', 'partner', or 'rejected')
     * @return int
     */
    public function countIndustryByConfirmStatus($confirmStatus)
    {
        if ($confirmStatus == 'unconfirmed') {
            return Industry::where('status', '0')->count();
        } elseif ($confirmStatus == 'partner') {
            return Industry::where('status', '1')->count();
        } elseif ($confirmStatus == 'rejected') {
            return Industry::where('status', '2')->count();
        }
    }

    /**
     * Find an industry by its ID.
     *
     * @param  int  $id
     * @return Industry|null
     */
    public function findIndustryById($id)
    {
        return Industry::find($id);
    }

    /**
     * Create a new industry.
     *
     * @param  array  $data
     * @return Industry
     */
    public function createIndustry(array $data)
    {
        return Industry::create($data);
    }

    /**
     * Update an industry by ID.
     *
     * @param  int    $id
     * @param  array  $data
     * @return bool
     */
    public function updateIndustry($id, array $data)
    {
        return Industry::where('id', $id)->update($data);
    }

    /**
     * Delete an industry by ID.
     *
     * @param  int  $id
     * @return bool
     */
    public function deleteIndustry($id)
    {
        return Industry::where('id', $id)->delete();
    }
}
