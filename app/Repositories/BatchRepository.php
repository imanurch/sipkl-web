<?php

namespace App\Repositories;

use App\Models\Batch;

class BatchRepository
{
    /**
     * Get all batches with optional search filter.
     *
     * @param string|null $searchFilters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllBatch($searchFilters = null)
    {
        $query = Batch::query();

        if ($searchFilters != null) {
            $query->where('name', 'like', '%' . $searchFilters . '%')
                ->orWhere('year', 'like', '%' . $searchFilters . '%');
        }

        return $query->paginate(5);
    }

    /**
     * Get batch based on active/inactive status.
     *
     * @param string $status
     * @return \App\Models\Batch|\Illuminate\Database\Eloquent\Collection|null
     */
    public function getBatchByStatus($status)
    {
        if ($status == 'active') {
            return Batch::where('status', '1')->first();
        } else {
            return Batch::where('status', '0')->get();
        }
    }

    /**
     * Get active batch or latest if none is active.
     *
     * @return \App\Models\Batch|null
     */
    public function getActiveOrLastBatch()
    {
        $activeBatch = Batch::where('status', '1')->first();

        if ($activeBatch == null) {
            return Batch::latest('id')->first();
        }

        return $activeBatch;
    }

    /**
     * Find batch by its ID.
     *
     * @param int $id
     * @return \App\Models\Batch|null
     */
    public function findBatchById($id)
    {
        return Batch::find($id);
    }

    /**
     * Create a new batch.
     *
     * @param array $data
     * @return \App\Models\Batch
     */
    public function createBatch(array $data)
    {
        return Batch::create($data);
    }

    /**
     * Update an existing batch.
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function updateBatch($id, array $data)
    {
        return Batch::where('id', $id)->update($data);
    }

    /**
     * Delete batch by its ID.
     *
     * @param int $id
     * @return int
     */
    public function deleteBatch($id)
    {
        return Batch::where('id', $id)->delete();
    }
}
