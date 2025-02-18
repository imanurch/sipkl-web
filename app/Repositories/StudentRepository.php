<?php

namespace App\Repositories;

use App\Models\Student;

class StudentRepository
{
    public function getStudent(array $filters = [])
    {
        // return Student::get();
        $query = Student::query();
        $batch_id = $filters['batch_id'];
        $year = $filters['year'];

        // filter department
        if ($filters['department'] != null) {
            $department_id = ($filters['department'] == 'K3R' ? '1' : ($filters['department'] == 'DPIB' ? '2' : '3'));
            $query->where('department_id', $department_id);
        }

        if ($filters['year'] != null) {
            $query->where('year', $year);
        }

        // filter status
        if ($filters['status'] != null) {
            if ($filters['status'] == 'registered') {
                $query->whereHas('groupMember.group.registration', function ($query) use ($batch_id, $year) {
                    $query->where('batch_id', $batch_id)->where('year', $year);
                });
            } elseif ($filters['status'] == 'unregistered') {
                $query->whereDoesntHave('groupMember.group.registration', function ($query) use ($batch_id, $year) {
                    $query->where('batch_id', $batch_id)->where('year', $year);
                });
            }
        }

        // filter search
        if ($filters['search'] != null) {
            $query->where(function ($subQuery) use ($filters) {
                $subQuery->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('nisn', 'like', '%' . $filters['search'] . '%');
            });
        }

        // belum tambah kolom status
        $data = $query->paginate(5);
        $data->appends($filters);
        // $data->through(function ($student) use ($batch_id) {
        //     // Pastikan relasi ada sebelum mengaksesnya
        //     $groupMember = $student->groupMember;
        //     $group = $groupMember ? $groupMember->group : null;
        //     $registration = $group ? $group->registration : null;
        //     // Jika ada groupMember, group, dan registration, baru lakukan pengecekan
        //     $statusCheck = $registration
        //         ? $registration->where('batch_id', $batch_id)->whereIn('status', ['0', '1'])->get()
        //         : collect(); // Jika tidak ada registration, gunakan koleksi kosong

        //     // Set status berdasarkan hasil pengecekan
        //     $student->setAttribute('status', $statusCheck->isNotEmpty() ? 'Terdaftar' : 'Belum Terdaftar');

        //     // $student->setAttribute('status', $student->groupMember->group->registration->where('batch_id', $batch_id)->whereIn('status', ['0', '1'])->get()->isNotEmpty() ? 'Terdaftar' : 'Belum Terdaftar');
        //     return $student;
        // });

        // return $query->get();
        return $data;
    }

    public function getNonInternStudentList($activeBatch_id)
    {
        return Student::whereDoesntHave('groupMember.group.internship', function ($query) use ($activeBatch_id) {
            $query->where('batch_id', $activeBatch_id);
        })->select('id', 'name', 'nisn')->get();
    }

    public function countStudentByStatus($year, $batch_id, $status)
    {
        if ($status == 'registered') {
            return Student::whereHas('groupMember.group.registration', function ($query) use ($batch_id) {
                $query->where('batch_id', $batch_id)->whereIn('status', ['0', '1']);
            })->where('year', $year)->count();
        }
        
        elseif ($status == 'unregistered') {
            return Student::whereDoesntHave('groupMember.group.registration', function ($query) use ($batch_id) {
                $query->where('batch_id', $batch_id);
            })->orWhereHas('groupMember.group.registration', function ($query) use ($batch_id) {
                $query->where('batch_id', $batch_id)->where('status','2');
            })->where('year', $year)->count();
        }
    }

    public function getStudentYear()
    {
        return Student::select('year')->distinct()->get();
    }

    public function findStudentById($id)
    {
        return Student::find($id);
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
