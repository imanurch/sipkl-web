<?php

namespace App\Repositories;

use App\Models\Industry;

class IndustryRepository
{
    public function getUnconfirmedIndustry($filters = [])
    {
        $query = Industry::query();

        if ($filters != null) {
            // filter search
            if ($filters['unconfirmedIndustrySearch'] != null) {
                $query->where('name', 'like', '%' . $filters['unconfirmedIndustrySearch'] . '%');
            }
        }

        return $query->where('status', '0')->orderBy('created_at', 'desc')->paginate(10)->appends([
            'tab' => 'rejected',
            'unconfirmedIndustrySearch' => $filters['unconfirmedIndustrySearch'] ?? '',
        ]);
    }

    public function getPartnerIndustry($filters = [], $batch_id)
    {
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

        // $data = $query->where('status', '1')->paginate(5);

        // $data = $query->paginate(5);
        // $data->appends($filters);
        // $data->through(function ($industry) use ($batch_id) {
        //     $industry->setAttribute('status', $industry->internship->where('batch_id', $batch_id)->isNotEmpty() ? 'Aktif' : 'Non Aktif');
        //     return $industry;
        // });

        // return $data;

        // Pastikan status = 1 (mitra yang sudah disetujui misalnya)
        $query->where('status', '1');

        // Paginate dan tambahkan query string (tab + filter yang relevan)
        $data = $query->orderBy('created_at', 'desc')->paginate(10)->appends([
            'tab' => 'partner',
            'partnerIndustrySearch' => $filters['partnerIndustrySearch'] ?? '',
            'status' => $filters['status'] ?? '',
        ]);

        // Ubah status aktif/non-aktif berdasarkan relasi
        $data->through(function ($industry) use ($batch_id) {
            $industry->setAttribute(
                'status',
                $industry->internship->where('batch_id', $batch_id)->isNotEmpty() ? 'Aktif' : 'Non Aktif'
            );
            return $industry;
        });

        return $data;
    }

    public function getRejectedIndustry($filters = [])
    {
        $query = Industry::query();

        // filter search
        if ($filters['rejectedIndustrySearch'] != null) {
            $query->where('name', 'like', '%' . $filters['rejectedIndustrySearch'] . '%');
        }

        return $query->where('status', '2')->orderBy('created_at', 'desc')->paginate(10)->appends([
            'tab' => 'rejected',
            'rejectedIndustrySearch' => $filters['rejectedIndustrySearch'] ?? '',
        ]);
    }

    public function countIndustryByStatus($batch_id, $status)
    {
        if ($status == 'active') {
            return Industry::whereHas('internship', function ($query) use ($batch_id) {
                $query->where('batch_id', $batch_id);
            })->count();
        } elseif ($status == 'inactive') {
            return Industry::where('status', '1')->whereDoesntHave('internship', function ($query) use ($batch_id) {
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

    public function getActivePartnerIndustryList($batch_id)
    {
        return Industry::where('status', '1')->whereHas('internship', function ($query) use ($batch_id) {
            $query->where('batch_id', $batch_id);
        })->get();
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
        if ($status == 'accept') {
            return Industry::where('id', $id)->update(['status' => '1']);
        } else if ($status == 'reject') {
            return Industry::where('id', $id)->update(['status' => '2']);
        }
    }

    public function deleteIndustry($id)
    {
        return Industry::where('id', $id)->delete();
    }
}
