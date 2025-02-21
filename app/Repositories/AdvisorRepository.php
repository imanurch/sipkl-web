<?php

namespace App\Repositories;

use App\Models\Advisor;

class AdvisorRepository
{
    public function getAdvisor(array $filters = [])
    {
        $query = Advisor::query();
        $batch_id = $filters['batch_id'];

        // filter department
        if ($filters['department'] != null) {
            $department_id = ($filters['department'] == 'K3R' ? '1' : ($filters['department'] == 'DPIB' ? '2' : '3'));
            $query->where('department_id', $department_id);
        }

        // filter status
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

        // filter search
        if ($filters['search'] != null) {
            $query->where(function ($subQuery) use ($filters) {
                $subQuery->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('nip', 'like', '%' . $filters['search'] . '%');
            });
        }

        $data = $query->paginate(5);
        $data->appends($filters);
        $data->through(function ($advisor) use ($batch_id) {
            $advisor->setAttribute('status', $advisor->internship->where('batch_id', $batch_id)->isNotEmpty() ? 'Aktif' : 'Non Aktif');
            return $advisor;
        });

        return $data;
    }

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

    public function getAdvisorList()
    {
        return Advisor::select('id', 'name', 'nip', 'department_id')->get();
    }

    public function findAdvisorById($id)
    {
        return Advisor::find($id);
    }

    // public function getAdvisorIdByUserId($user_id)
    // {
    //     return Advisor::where('user_id', $user_id)->select('id')->first();
    // }

    public function getAdvisorByUserId($user_id)
    {
        return Advisor::where('user_id', $user_id)->first();
    }

    public function createAdvisor(array $data)
    {
        return Advisor::create($data);
    }

    public function updateAdvisor($id, array $data)
    {
        return Advisor::where('id', $id)->update($data);
    }

    public function deleteAdvisor($id)
    {
        return Advisor::where('id', $id)->delete();
    }
}
