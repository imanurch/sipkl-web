<?php

namespace App\Repositories;

use App\Models\Advisor;
// use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\AbstractPaginator;

class AdvisorRepository
{
    public function getAdvisor(array $filters = [])
    {
        // return Advisor::get();
        // dd($filters);

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

        // return $query->get();
        // return $query->get()->map(function ($advisor) use ($batch_id){
        //     $advisor->setAttribute('status', $advisor->internship->where('batch_id', $batch_id)->isNotEmpty() ? 'Aktif' : 'Non Aktif');
        //     return $advisor;
        // });

        // $data = $query->paginate(1)->withQueryString();
        $data = $query->paginate(5);
        $data->appends($filters);
        $data->through(function ($advisor) use ($batch_id) {
            $advisor->setAttribute('status', $advisor->internship->where('batch_id', $batch_id)->isNotEmpty() ? 'Aktif' : 'Non Aktif');
            return $advisor;
        });

        // $data = $query->paginate(5);
        // dd(get_class($data));

        // Map data di dalam `items`
        // $data->getCollection()->transform(function ($advisor) use ($batch_id) {
        //     $advisor->setAttribute('status', $advisor->internships->where('batch_id', $batch_id)->isNotEmpty() ? 'Aktif' : 'Non Aktif');
        //     return $advisor;
        // });

        return $data;
    }

    // public function getAdvisorByDepartment($department_id)
    // {
    //     return Advisor::where('department_id', $department_id)->get();
    // }

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

    // public function countActiveAdvisor($batch_id)
    // {
    //     return Advisor::whereHas('internships', function ($query) use ($batch_id) {
    //         $query->where('batch_id', $batch_id);
    //     })->count();
    // }

    // public function countInactiveAdvisor($batch_id)
    // {
    //     return Advisor::whereDoesntHave('internships', function ($query) use ($batch_id) {
    //         $query->where('batch_id', $batch_id);
    //     })->count();
    // }

    public function findAdvisorById($id)
    {
        return Advisor::find($id);
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
