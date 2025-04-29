<?php

namespace App\Repositories;

use App\Models\Industry;

class IndustryUnconfirmedRepository
{
    /**
     * Get unconfirmed industries with optional search filter.
     *
     * @param  array  $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUnconfirmedIndustry($filters = [])
    {
        $query = Industry::query();

        if ($filters != null) {
            // Apply search filter by industry name
            if ($filters['unconfirmedIndustrySearch'] != null) {
                $query->where('name', 'like', '%' . $filters['unconfirmedIndustrySearch'] . '%');
            }
        }

        // Return paginated list of unconfirmed industries
        return $query->where('status', '0')->orderBy('created_at', 'desc')->paginate(10)->appends([
            'tab' => 'rejected',
            'unconfirmedIndustrySearch' => $filters['unconfirmedIndustrySearch'] ?? '',
        ]);
    }

    /**
     * Update industry confirmation status (accept/reject).
     *
     * @param  int     $id
     * @param  string  $status
     * @return int
     */
    public function updateIndustryRequestStatus($id, $status)
    {
        if ($status == 'accept') {
            return Industry::where('id', $id)->update(['status' => '1']); // Set as partner
        } else if ($status == 'reject') {
            return Industry::where('id', $id)->update(['status' => '2']); // Set as rejected
        }
    }
}
