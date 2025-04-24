<?php

namespace App\Repositories;

use App\Models\Batch;

class BatchRepository
{
    public function getAllBatch($searchFilters = null)
    {
        $query = Batch::query();

        if ($searchFilters != null) {
            $query->where('name', 'like', '%' . $searchFilters . '%')->orWhere('year', 'like', '%' . $searchFilters . '%');
        }

        return $query->paginate(5);
    }

    // public function getBatchByStatus($status)
    // {
    //     if ($status == 'active') {
    //         return Batch::where('status', '1')->first();
    //     } else {
    //         return Batch::where('status', '0')->get();
    //     }
    // }

    public function getBatchByNonActiveStatus()
    {
        return Batch::where('status', '0')->first();
    }

    public function getBatchByActiveStatus()
    {
        return Batch::where('status', '1')->first();
    }

    // public function getActiveOrLastBatch()
    // {
    //     $activeBatch = Batch::where('status', '1')->first();

    //     if ($activeBatch == null) {
    //         return Batch::latest('id')->first();
    //     } else {
    //         return $activeBatch;
    //     }
    // }

    public function getActiveBatch()
    {
        return Batch::where('status', '1')->first();
    }

    public function getLastBatch()
    {
        return Batch::latest('id')->first();
    }

    public function findBatchById($id)
    {
        return Batch::find($id);
    }

    public function createBatch(array $data)
    {
        return Batch::create($data);
    }

    public function updateBatch($id, array $data)
    {
        return Batch::where('id', $id)->update($data);
    }

    // public function setActiveBatch($id)
    // {
    //     Batch::where('status', '1')->update(['status' => '0']);
    //     return Batch::where('id', $id)->update(['status' => '1']);
    // }

    public function deactivateCurrentActiveBatch()
    {
        return Batch::where('status', '1')->update(['status' => '0']);
    }

    public function setActiveBatch($id)
    {
        return Batch::where('id', $id)->update(['status' => '1']);
    }

    public function deleteBatch($id)
    {
        return Batch::where('id', $id)->delete();
    }
}
