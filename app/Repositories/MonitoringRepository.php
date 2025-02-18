<?php

namespace App\Repositories;

use App\Models\Monitoring;

class MonitoringRepository
{
    public function getMonitoringByAdvisorIdAndBatch($advisor_id, $batch_id, $filters = [])
    {
        $query = Monitoring::query();

        // filter type
        if ($filters['type'] != null) {
            $query->where('type', $filters['type']);
        }

        // filter search
        if ($filters['search'] != null) {
            $query->where(function ($subQuery) use ($filters) {
                $subQuery->whereHas('internship', function ($nestedSubQuery) use ($filters) {
                    $nestedSubQuery->where('group_id', 'like', '%' . $filters['search'] . '%');
                })
                    ->orWhereHas('internship.industry', function ($nestedSubQuery) use ($filters) {
                        $nestedSubQuery->where('name', 'like', '%' . $filters['search'] . '%');
                    })
                ;
            });
        }

        return $query->whereHas('internship', function ($query) use ($batch_id, $advisor_id) {
            $query->where('advisor_id', $advisor_id)->where('batch_id', $batch_id);
        })->with('monitoringDocument')->get();
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
