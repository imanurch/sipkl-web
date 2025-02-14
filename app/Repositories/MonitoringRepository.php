<?php

namespace App\Repositories;

use App\Models\Monitoring;

class MonitoringRepository
{
    public function getByAdvisorIdAndBatch($advisor_id, $batch_id)
    {
        return Monitoring::whereHas('internships', function ($query) use ($batch_id, $advisor_id) {
            $query->where('advisor_id', $advisor_id)->where('batch_id', $batch_id);
        })->get();
    }

    public function findById($id)
    {
        return Monitoring::find($id);
    }

    public function createMonitoring(array $data)
    {
        return Monitoring::create($data);
    }

    public function updateMonitoring($id, array $data)
    {
        return Monitoring::where('id', $id)->update($data);
    }
    
    public function deleteMonitoring($id)
    {
        return Monitoring::where('id', $id)->delete();
    }
}