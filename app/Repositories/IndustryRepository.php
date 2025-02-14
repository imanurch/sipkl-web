<?php

namespace App\Repositories;

use App\Models\Industry;

class IndustryRepository
{
    public function getUnconfirmedIndustry($filters = [])
    {
        // return Industry::get();
        $query = Industry::query();

        // filter search
        if ($filters['unconfirmedIndustrySearch'] != null) {
            $query->where('name', 'like', '%' . $filters['unconfirmedIndustrySearch'] . '%');
        }

        return $query->where('status', '0')->get();
    }

    public function getPartnerIndustry($filters = [], $batch_id)
    {
        // return Industry::get();
        $query = Industry::query();

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
        if ($filters['partnerIndustrySearch'] != null) {
            $query->where('name', 'like', '%' . $filters['partnerIndustrySearch'] . '%');
        }

        return $query->where('status', '1')->get();

        // $data = $query->paginate(5);
        // // dd($data);
        // $data->appends($filters);
        // $data->through(function ($industry) use ($batch_id) {
        //     $industry->setAttribute('status', $industry->internship->where('batch_id', $batch_id)->isNotEmpty() ? 'Aktif' : 'Non Aktif');
        //     return $industry;
        // });

        // return $data;
    }

    public function getRejectedIndustry($filters = [])
    {
        // return Industry::get();
        $query = Industry::query();

        // filter search
        if ($filters['rejectedIndustrySearch'] != null) {
            $query->where('name', 'like', '%' . $filters['rejectedIndustrySearch'] . '%');
        }

        return $query->where('status', '2')->get();
    }

    public function countIndustryByStatus($batch_id, $status)
    {
        // ga valid ntah kenapa
        if ($status == 'active') {
            return Industry::whereHas('internship', function ($query) use ($batch_id) {
                $query->where('batch_id', $batch_id);
            })->count();
        } elseif ($status == 'inactive') {
            return Industry::whereDoesntHave('internship', function ($query) use ($batch_id) {
                $query->where('batch_id', $batch_id);
            })->count();
        }
    }

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

    public function getPartnerIndustryList()
    {
        return Industry::where('status', '1')->get();
    }

    public function findIndustryById($id)
    {
        return Industry::find($id);
    }

    public function createIndustry(array $data)
    {
        return Industry::create($data);
    }

    public function updateIndustry($id, array $data)
    {
        return Industry::where('id', $id)->update($data);
    }

    public function updateIndustryRequestStatus($id, $status)
    {
        return Industry::where('id', $id)->update(['status' => $status]);
    }

    public function deleteIndustry($id)
    {
        return Industry::where('id', $id)->delete();
    }
}
