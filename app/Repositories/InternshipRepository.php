<?php

namespace App\Repositories;

use App\Models\Student;
use App\Models\Industry;
use App\Models\Internship;
use App\Models\GroupMember;

class InternshipRepository
{
    public function getInternship($filters = [])
    {        
        $query = Internship::with(
            'group',
            'group.groupMember.student:id,name,department_id',
            'group.groupMember.student.department:id,name',
            'industry:id,name',
            'advisor:id,name'
        );

        // filter batch
        if ($filters['batch_id'] != null) {
            $query->where('batch_id', $filters['batch_id']);
        };

        // filter search
        if ($filters['search'] != null) {
            $query->whereHas('group', function ($query) use ($filters) {
                $query->where('name', 'like', '%' . $filters['search'] . '%');
            });
        };

        return $query->paginate(5);
    }

    public function getIntern($filters = [])
    {
        // $query = Student::whereHas('groupMember.group.internship', function ($query) use ($filters) {
        //     $query->where('batch_id', $filters['batch_id']);
        // });

        $query = Student::with(
            'groupMember.group.internship',
            'groupMember.group.internship.advisor:id,name',
            'groupMember.group.internship.industry:id,name',
        )->whereHas('groupMember.group.internship', function ($query) use ($filters) {
            $query->where('batch_id', $filters['batch_id']);
        });

        // filter search
        if ($filters['search'] != null) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        };

        return $query->paginate(5);
    }

    public function countIntern($batch_id)
    {
        return Student::whereHas('groupMember.group.internship', function ($query) use ($batch_id) {
            $query->where('batch_id', $batch_id);
        })->count();
    }

    // public function getInternByAdvisor($batch_id, $advisor_id){
    //     return Student::whereHas('groupMember.group.internship', function ($query) use ($advisor_id, $batch_id) {
    //         $query->where('advisor_id', $advisor_id)->where('batch_id', $batch_id);
    //     })->with('groupMember.group.internship.industry:id,name')->get();
    // }

    public function getInternByAdvisor($filters = [], $advisor_id)
    {
        $query = Student::query();

        // filter search
        if ($filters['search'] != null) {
            $query->where(function ($subQuery) use ($filters) {
                $subQuery->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('nisn', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->whereHas('groupMember.group.internship', function ($query) use ($advisor_id, $filters) {
            $query->where('advisor_id', $advisor_id)->where('batch_id', $filters['batch_id']);
        })->with('groupMember.group.internship.industry:id,name')->paginate(5);
    }

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

    public function countInternByAdvisor($batch_id, $advisor_id)
    {
        return Student::whereHas('groupMember.group.internship', function ($query) use ($advisor_id, $batch_id) {
            $query->where('advisor_id', $advisor_id)->where('batch_id', $batch_id);
        })->count();
    }

    public function countIndustryByAdvisor($batch_id, $advisor_id)
    {
        return Industry::whereHas('internship', function ($query) use ($advisor_id, $batch_id) {
            $query->where('advisor_id', $advisor_id)->where('batch_id', $batch_id);
        })->count();
    }

    public function getInternshipByStudentId($batch_id, $student_id)
    {
        return Internship::whereHas('group.groupMember.student', function ($query) use ($student_id, $batch_id) {
            $query->where('id', $student_id);
        })->with('industry:id,name,address', 'advisor:id,name,phone_num')->where('batch_id', $batch_id)->first();
    }




    // public function findInternshipById($id)
    // {
    //     return Internship::find($id);
    // }

    // public function createInternship(array $data)
    // {
    //     return Internship::create($data);
    // }

    // public function updateInternship($id, array $data)
    // {
    //     return Internship::where('id', $id)->update($data);
    // }

    // public function deleteInternship($id)
    // {
    //     return Internship::where('id', $id)->delete();
    // }
}
