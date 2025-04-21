<?php

namespace App\Repositories;

use App\Models\Student;

class StudentRepository
{
    public function getStudent(array $filters = [])
    {
        $query = Student::query();

        // filter department
        if ($filters['department'] != null) {
            $department_id = ($filters['department'] == 'RPL' ? '1' : ($filters['department'] == 'DPIB' ? '2' : '3'));
            $query->where('department_id', $department_id);
        }

        // filter status
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

        // filter search
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

        // belum tambah kolom status
        $data = $query->where('year', $filters['year'])->orderBy('created_at', 'desc')->paginate(10);
        $data->appends($filters);
        return $data;
    }

    public function getNonRegisteredInternList($activeBatch_id, $student_department)
    {
        return Student::whereDoesntHave('groupMember.group.internship', function ($query) use ($activeBatch_id) {
            $query->where('batch_id', $activeBatch_id);
        })->WhereDoesntHave('groupMember.group.registration', function ($query) {
            $query->where('status', ['0', '1']);
        })->where('department_id', $student_department)->select('id', 'name', 'nis')->get();
    }

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

    public function getStudentYear()
    {
        return Student::select('year')->distinct()->get();
    }

    public function getLastYearStudent()
    {
        return Student::select('year')->latest()->first();
    }

    public function findStudentById($id)
    {
        return Student::find($id);
    }

    public function getStudentByUserId($user_id)
    {
        return Student::where('user_id', $user_id)->first();
    }

    public function createStudent(array $data)
    {
        return Student::create($data);
    }

    public function updateStudent($id, array $data)
    {
        return Student::where('id', $id)->update($data);
    }

    public function deleteStudent($id)
    {
        return Student::where('id', $id)->delete();
    }
}
