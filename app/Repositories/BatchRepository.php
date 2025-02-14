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

        $data = $query->paginate(5);

        $data->getCollection()->transform(function ($item) {
            $item->status = $item->status == 1 ? 'Aktif' : 'Non Aktif';
            return $item;
        });

        return $data;
    }

    public function getBatchByStatus($status)
    {
        // $status_id = ($status=='active' ? '1' : '0');
        // return Batch::where('status', $status_id)->get();
        if ($status == 'active') {
            return Batch::where('status', '1')->first();
        } else {
            return Batch::where('status', '0')->get();
        }
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

    public function deleteBatch($id)
    {
        return Batch::where('id', $id)->delete();
    }
}
