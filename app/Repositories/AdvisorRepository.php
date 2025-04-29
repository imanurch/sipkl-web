<?php

namespace App\Repositories;

use App\Models\Advisor;

class AdvisorRepository
{
    /**
     * Get a filtered and paginated list of advisors.
     *
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAdvisor(array $filters = [])
    {
        $query = Advisor::query();
        $batch_id = $filters['batch_id'];

        // Filter by department
        if ($filters['department'] != null) {
            $department_id = ($filters['department'] == 'RPL' ? '1' : ($filters['department'] == 'DPIB' ? '2' : '3'));
            $query->where('department_id', $department_id);
        }

        // Filter by advisor activity status
        if ($filters['status'] != null) {
            if ($filters['status'] == 'active') {
                $query->whereHas('internship', function ($query) use ($batch_id) {
                    $query->where('batch_id', $batch_id);
                });
            } elseif ($filters['status'] == 'inactive') {
                $query->whereDoesntHave('internship', function ($query) use ($batch_id) {
                    $query->where('batch_id', $batch_id);
                });
            }
        }

        // Filter by search keyword
        if ($filters['search'] != null) {
            $query->where(function ($subQuery) use ($filters) {
                $subQuery->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('nip', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('phone_num', 'like', '%' . $filters['search'] . '%')
                    ->orWhereHas('user', function ($subSubQuery) use ($filters) {
                        $subSubQuery->where('username', 'like', '%' . $filters['search'] . '%')
                            ->orWhere('email', 'like', '%' . $filters['search'] . '%');
                    });
            });
        }

        $data = $query->with('advisorPosition', 'advisorLevel')->orderBy('created_at', 'desc')->paginate(10);
        $data->appends($filters);

        // Set status attribute for each advisor
        $data->through(function ($advisor) use ($batch_id) {
            $advisor->setAttribute('status', $advisor->internship->where('batch_id', $batch_id)->isNotEmpty() ? 'Aktif' : 'Non Aktif');
            return $advisor;
        });

        return $data;
    }

    /**
     * Count advisors based on activity status.
     *
     * @param int $batch_id
     * @param string $status
     * @return int
     */
    public function countAdvisorsByStatus($batch_id, $status)
    {
        if ($status == 'active') {
            return Advisor::whereHas('internship', function ($query) use ($batch_id) {
                $query->where('batch_id', $batch_id);
            })->count();
        } elseif ($status == 'inactive') {
            return Advisor::whereDoesntHave('internship', function ($query) use ($batch_id) {
                $query->where('batch_id', $batch_id);
            })->count();
        }
    }

    /**
     * Get all advisors.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAdvisorList()
    {
        return Advisor::get();
    }

    /**
     * Create a new advisor record.
     *
     * @param array $data
     * @return \App\Models\Advisor
     */
    public function createAdvisor(array $data)
    {
        return Advisor::create($data);
    }

    /**
     * Update an existing advisor record.
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function updateAdvisor($id, array $data)
    {
        return Advisor::where('id', $id)->update($data);
    }

    /**
     * Delete an advisor by ID.
     *
     * @param int $id
     * @return int
     */
    public function deleteAdvisor($id)
    {
        return Advisor::where('id', $id)->delete();
    }
}
