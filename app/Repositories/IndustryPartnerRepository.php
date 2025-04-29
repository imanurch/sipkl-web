<?php

namespace App\Repositories;

use App\Models\Industry;

class IndustryPartnerRepository
{
    /**
     * Get a paginated list of partner industries based on filters and batch.
     *
     * @param  array  $filters
     * @param  int    $batch_id
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPartnerIndustry($filters = [], $batch_id)
    {
        $query = Industry::query();

        // Filter by internship status
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
        if ($filters['partnerIndustrySearch'] != null) {
            $query->where('name', 'like', '%' . $filters['partnerIndustrySearch'] . '%');
        }

        // Only get active industries
        $query->where('status', '1');

        $data = $query->orderBy('created_at', 'desc')->paginate(10)->appends([
            'tab' => 'partner',
            'partnerIndustrySearch' => $filters['partnerIndustrySearch'] ?? '',
            'status' => $filters['status'] ?? '',
        ]);

        // Add status attribute based on internship existence
        $data->through(function ($industry) use ($batch_id) {
            $industry->setAttribute(
                'status',
                $industry->internship->where('batch_id', $batch_id)->isNotEmpty() ? 'Aktif' : 'Non Aktif'
            );
            return $industry;
        });

        return $data;
    }

    /**
     * Get all active partner industries.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPartnerIndustryList()
    {
        return Industry::where('status', '1')->get();
    }

    /**
     * Get active partner industries that have internship in the given batch.
     *
     * @param  int  $batch_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActivePartnerIndustryList($batch_id)
    {
        return Industry::where('status', '1')->whereHas('internship', function ($query) use ($batch_id) {
            $query->where('batch_id', $batch_id);
        })->get();
    }
}
