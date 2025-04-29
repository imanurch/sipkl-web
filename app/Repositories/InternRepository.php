<?php

namespace App\Repositories;

use App\Models\Student;

class InternRepository
{
    /**
     * Get paginated list of interns based on filters such as batch and search keyword.
     *
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getIntern($filters = [])
    {
        $query = Student::with(
            'groupMember.group.internship',
            'groupMember.group.internship.advisor:id,name',
            'groupMember.group.internship.industry:id,name',
            'internDocument'
        )->whereHas('groupMember.group.internship', function ($query) use ($filters) {
            $query->where('batch_id', $filters['batch_id']);
        });

        // filter search
        if ($filters['search'] != null) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        };

        if ($filters['search'] != null) {
            $query->where('name', 'like', '%' . $filters['search'] . '%')
                ->orWhereHas('groupMember.group.internship.industry', function ($subQuery) use ($filters) {
                    $subQuery->where('name', 'like', '%' . $filters['search'] . '%');
                });
        };

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    /**
     * Get all interns by batch ID without pagination.
     *
     * @param int $batch_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllIntern($batch_id)
    {
        return Student::with(
            'groupMember.group.internship',
            'groupMember.group.internship.advisor:id,name',
            'groupMember.group.internship.industry:id,name',
            'internDocument'
        )->whereHas('groupMember.group.internship', function ($query) use ($batch_id) {
            $query->where('batch_id', $batch_id);
        })->get();
    }

    /**
     * Count the number of interns by batch ID.
     *
     * @param int $batch_id
     * @return int
     */
    public function countIntern($batch_id)
    {
        return Student::whereHas('groupMember.group.internship', function ($query) use ($batch_id) {
            $query->where('batch_id', $batch_id);
        })->count();
    }
}
