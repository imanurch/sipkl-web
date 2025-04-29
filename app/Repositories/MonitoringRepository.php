<?php

namespace App\Repositories;

use App\Models\Monitoring;

class MonitoringRepository
{
    /**
     * Get paginated monitoring data by batch ID and optional filters.
     *
     * @param int $batch_id
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getMonitoring($batch_id, $filters = [])
    {
        $query = Monitoring::query();

        // filter type
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // filter search
        if (!empty($filters['search'])) {
            $query->whereHas('internship.advisor', function ($subQuery) use ($filters) {
                $subQuery->where('name', 'like', '%' . $filters['search'] . '%');
            })->orWhereHas('internship.industry', function ($subQuery) use ($filters) {
                $subQuery->where('name', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->whereHas('internship', function ($query) use ($batch_id) {
            $query->where('batch_id', $batch_id);
        })->with('monitoringDocument')->orderBy('created_at', 'desc')->paginate(10);
    }

    /**
     * Get paginated monitoring data by advisor ID, batch ID, and optional filters.
     *
     * @param int $advisor_id
     * @param int $batch_id
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getMonitoringByAdvisorIdAndBatch($advisor_id, $batch_id, $filters = [])
    {
        $query = Monitoring::query();

        // filter type
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // filter search
        if (!empty($filters['search'])) {
            $query->where(function ($subQuery) use ($filters) {
                $subQuery->whereHas('internship', function ($nestedSubQuery) use ($filters) {
                    $nestedSubQuery->where('group_id', 'like', '%' . $filters['search'] . '%');
                })->orWhereHas('internship.industry', function ($nestedSubQuery) use ($filters) {
                    $nestedSubQuery->where('name', 'like', '%' . $filters['search'] . '%');
                });
            });
        }

        return $query->whereHas('internship', function ($query) use ($batch_id, $advisor_id) {
            $query->where('advisor_id', $advisor_id)->where('batch_id', $batch_id);
        })->with('monitoringDocument')->orderBy('created_at', 'desc')->paginate(10);
    }

    /**
     * Find monitoring by ID.
     *
     * @param int $id
     * @return \App\Models\Monitoring|null
     */
    public function findById($id)
    {
        return Monitoring::find($id);
    }

    /**
     * Create a new monitoring record.
     *
     * @param array $data
     * @return \App\Models\Monitoring
     */
    public function createMonitoring(array $data)
    {
        return Monitoring::create($data);
    }

    /**
     * Update monitoring record by ID.
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function updateMonitoring($id, array $data)
    {
        return Monitoring::where('id', $id)->update($data);
    }

    /**
     * Delete monitoring record by ID.
     *
     * @param int $id
     * @return int
     */
    public function deleteMonitoring($id)
    {
        return Monitoring::where('id', $id)->delete();
    }
}
