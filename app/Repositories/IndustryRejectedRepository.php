<?php

namespace App\Repositories;

use App\Models\Industry;

class IndustryRejectedRepository
{
    /**
     * Get paginated list of rejected industries with optional search filter.
     *
     * @param  array  $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getRejectedIndustry($filters = [])
    {
        $query = Industry::query();

        // Apply search filter if provided
        if (!empty($filters['rejectedIndustrySearch'])) {
            $query->where('name', 'like', '%' . $filters['rejectedIndustrySearch'] . '%');
        }

        // Filter only industries with 'rejected' status (status = 2)
        return $query->where('status', '2')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends([
                'tab' => 'rejected',
                'rejectedIndustrySearch' => $filters['rejectedIndustrySearch'] ?? '',
            ]);
    }
}
