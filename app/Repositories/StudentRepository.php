<?php

namespace App\Repositories;

use App\Models\Student;

class StudentRepository
{
    /**
     * Get a list of students based on filters (department, status, search, etc.).
     * 
     * @param array $filters
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getStudent(array $filters = [])
    {
        $query = Student::query();

        // Filter by department
        if ($filters['department'] != null) {
            $department_id = ($filters['department'] == 'RPL' ? '1' : ($filters['department'] == 'DPIB' ? '2' : '3'));
            $query->where('department_id', $department_id);
        }

        // Filter by registration status
        if ($filters['status'] != null) {
            if ($filters['status'] == 'registered') {
                $query->whereHas('groupMember.group.registration', function ($query) use ($filters) {
                    $query->where('batch_id', $filters['batch_id'])->where('year', $filters['year']);
                });
            } elseif ($filters['status'] == 'unregistered') {
                $query->whereDoesntHave('groupMember.group.registration', function ($query) use ($filters) {
                    $query->where('batch_id', $filters['batch_id'])->where('year', $filters['year']);
                });
            }
        }

        // Filter by search term
        if ($filters['search'] != null) {
            $query->where(function ($subQuery) use ($filters) {
                $subQuery->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('nisn', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('nis', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('phone_num', 'like', '%' . $filters['search'] . '%')
                    ->orWhereHas('user', function ($subSubQuery) use ($filters) {
                        $subSubQuery->where('username', 'like', '%' . $filters['search'] . '%')
                            ->orWhere('email', 'like', '%' . $filters['search'] . '%');
                    });
            });
        }

        // Apply the year filter and paginate the result (10 per page)
        $data = $query->where('year', $filters['year'])->orderBy('created_at', 'desc')->paginate(10);
        $data->appends($filters);  // Keep filters in pagination links
        return $data;
    }

    /**
     * Count the number of students with a specific registration status.
     * 
     * @param string $status
     * @param int $year
     * @param int $batch_id
     * @return int
     */
    public function countStudentByStatus($year, $batch_id, $status)
    {
        if ($status == 'registered') {
            return Student::where('year', $year)->whereHas('groupMember.group.registration', function ($query) use ($batch_id) {
                $query->where('batch_id', $batch_id)->whereIn('status', ['0', '1']);
            })->count();
        } elseif ($status == 'unregistered') {
            return Student::where('year', $year)->whereDoesntHave('groupMember.group.registration', function ($query) use ($batch_id) {
                $query->where('batch_id', $batch_id);
            })->orWhereHas('groupMember.group.registration', function ($query) use ($batch_id) {
                $query->where('batch_id', $batch_id)->where('status', '2');
            })->count();
        }
    }

    /**
     * Create a new student record.
     * 
     * @param array $data
     * @return \App\Models\Student
     */
    public function createStudent(array $data)
    {
        return Student::create($data);
    }

    /**
     * Update an existing student record.
     * 
     * @param int $id
     * @param array $data
     * @return int
     */
    public function updateStudent($id, array $data)
    {
        return Student::where('id', $id)->update($data);
    }

    /**
     * Delete a student record.
     * 
     * @param int $id
     * @return int
     */
    public function deleteStudent($id)
    {
        return Student::where('id', $id)->delete();
    }
}
