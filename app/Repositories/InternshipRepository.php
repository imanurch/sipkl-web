<?php

namespace App\Repositories;

use App\Models\Internship;

class InternshipRepository
{
    /**
     * Get paginated list of internships with related data and optional search filters.
     *
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getInternship($filters = [])
    {
        $query = Internship::with(
            'group',
            'group.groupMember.student:id,name,nis,nisn,department_id',
            'group.groupMember.student.department:id,name',
            'industry:id,name',
            'advisor:id,name',
            'internDocument'
        );

        // filter search
        if ($filters['search'] != null) {
            $query->whereHas('group', function ($subQuery) use ($filters) {
                $subQuery->where('name', 'like', '%' . $filters['search'] . '%');
            })->orWhereHas('industry', function ($subQuery) use ($filters) {
                $subQuery->where('name', 'like', '%' . $filters['search'] . '%');
            })->orWhereHas('advisor', function ($subQuery) use ($filters) {
                $subQuery->where('name', 'like', '%' . $filters['search'] . '%');
            })->orWhereHas('group.groupMember.student', function ($subQuery) use ($filters) {
                $subQuery->where('name', 'like', '%' . $filters['search'] . '%');
            });
        };

        return $query->where('batch_id', $filters['batch_id'])->orderBy('created_at', 'desc')->paginate(5);
    }

    /**
     * Get internship data by student ID and batch ID.
     *
     * @param int $batch_id
     * @param int $student_id
     * @return \App\Models\Internship|null
     */
    public function getInternshipByStudentId($batch_id, $student_id)
    {
        return Internship::whereHas('group.groupMember.student', function ($query) use ($student_id, $batch_id) {
            $query->where('id', $student_id);
        })->with('industry:id,name,address', 'advisor:id,name,phone_num')->where('batch_id', $batch_id)->first();
    }

    /**
     * Get all internships by batch ID.
     *
     * @param int $batch_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllInternshipList($batch_id)
    {
        return Internship::where('batch_id', $batch_id)->get();
    }

    /**
     * Get internship by group ID.
     *
     * @param int $group_id
     * @return \App\Models\Internship|null
     */
    public function getInternshipByGroupId($group_id)
    {
        return Internship::where('group_id', $group_id)->first();
    }

    /**
     * Find internship by ID.
     *
     * @param int $id
     * @return \App\Models\Internship|null
     */
    public function findInternshipById($id)
    {
        return Internship::find($id);
    }

    /**
     * Create a new internship record.
     *
     * @param array $data
     * @return \App\Models\Internship
     */
    public function createInternship(array $data)
    {
        return Internship::create($data);
    }

    /**
     * Delete internship by ID.
     *
     * @param int $id
     * @return int
     */
    public function deleteInternship($id)
    {
        return Internship::where('id', $id)->delete();
    }
}
