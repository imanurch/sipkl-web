<?php

namespace App\Repositories;

use App\Models\Student;
use App\Models\Industry;
use App\Models\Internship;

class InternshipByAdvisorRepository
{
    /**
     * Get paginated list of students assigned to a specific advisor with optional search and batch filter.
     *
     * @param array $filters
     * @param int $advisor_id
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getInternByAdvisor($filters = [], $advisor_id)
    {
        $query = Student::query();

        // filter search
        if ($filters['search'] != null) {
            $query->where(function ($subQuery) use ($filters) {
                $subQuery->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('nisn', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('nis', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('phone_num', 'like', '%' . $filters['search'] . '%')
                    ->orWhereHas('groupMember.group.internship.industry', function ($query) use ($filters) {
                        $query->where('name', 'like', '%' . $filters['search'] . '%');
                    });
            });
        }

        return $query->whereHas('groupMember.group.internship', function ($query) use ($advisor_id, $filters) {
            $query->where('advisor_id', $advisor_id)->where('batch_id', $filters['batch_id']);
        })->with('groupMember.group.internship.industry:id,name')->paginate(10);
    }

    /**
     * Get paginated list of industries linked to a specific advisor with optional search and batch filter.
     *
     * @param array $filters
     * @param int $advisor_id
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getIndustryByAdvisor($filters = [], $advisor_id)
    {
        $query = Industry::query();

        // filter search
        if ($filters['search'] != null) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        return $query->whereHas('internship', function ($query) use ($advisor_id, $filters) {
            $query->where('advisor_id', $advisor_id)->where('batch_id', $filters['batch_id']);
        })->paginate(5);
    }

    /**
     * Count the number of students assigned to a specific advisor for a given batch.
     *
     * @param int $batch_id
     * @param int $advisor_id
     * @return int
     */
    public function countInternByAdvisor($batch_id, $advisor_id)
    {
        return Student::whereHas('groupMember.group.internship', function ($query) use ($advisor_id, $batch_id) {
            $query->where('advisor_id', $advisor_id)->where('batch_id', $batch_id);
        })->count();
    }

    /**
     * Count the number of industries linked to a specific advisor for a given batch.
     *
     * @param int $batch_id
     * @param int $advisor_id
     * @return int
     */
    public function countIndustryByAdvisor($batch_id, $advisor_id)
    {
        return Industry::whereHas('internship', function ($query) use ($advisor_id, $batch_id) {
            $query->where('advisor_id', $advisor_id)->where('batch_id', $batch_id);
        })->count();
    }

    /**
     * Get list of internships assigned to a specific advisor for a given batch.
     *
     * @param int $advisor_id
     * @param int $batch_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getInternshipListByAdvisor($advisor_id, $batch_id)
    {
        return Internship::where('advisor_id', $advisor_id)->where('batch_id', $batch_id)->get();
    }

    /**
     * Update the advisor assigned to a specific internship.
     *
     * @param int $internship_id
     * @param int $advisor_id
     * @return int
     */
    public function updateInternshipAdvisor($internship_id, $advisor_id)
    {
        return Internship::where('id', $internship_id)->update(['advisor_id' => $advisor_id]);
    }
}
