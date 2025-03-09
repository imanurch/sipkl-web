<?php

namespace App\Repositories;

use App\Models\Internship;
use App\Models\Registration;

class RegistrationRepository
{
    public function getRegistration($filters = [])
    {
        $query = Registration::with(
            'group',
            'group.groupMember.student:id,name,department_id',
            'group.groupMember.student.department:id,name',
            'industry:id,name',
            'registrationDocument'
        );

        // filter status
        if ($filters['status'] != null) {
            if ($filters['status'] == 'unconfirmed') {
                $query->where('batch_id', $filters['batch_id'])->where('status', '0');
            } elseif ($filters['status'] == 'accepted') {
                $query->where('batch_id', $filters['batch_id'])->where('status', '1');
            } elseif ($filters['status'] == 'rejected') {
                $query->where('batch_id', $filters['batch_id'])->where('status', '2');
            }
        }

        // filter search
        if ($filters['search'] != null) {
            $query->whereHas('group', function ($query) use ($filters) {
                $query->where('name', 'like', '%' . $filters['search'] . '%');
            });
        };

        return $query->where('batch_id', $filters['batch_id'])->paginate(5);
    }

    public function countRegistrationByStatus($status, $batch_id)
    {
        if ($status == 'unconfirmed') {
            return Registration::where('batch_id', $batch_id)->where('status', '0')->count();
        } elseif ($status == 'accepted') {
            return Registration::where('batch_id', $batch_id)->where('status', '1')->count();
        } elseif ($status == 'rejected') {
            return Registration::where('batch_id', $batch_id)->where('status', '2')->count();
        }
    }

    public function findRegistrationById($id)
    {
        // return Registration::find($id);
        return Registration::with(
            'group',
            // 'group.groupMember:student_id',
            'group.groupMember.student:id,name,department_id',
            'group.groupMember.student.department:id,name',
            'industry:id,name',
            'registrationDocument'
        )->where('id', $id)->first();
    }

    public function getRegistrationByStudentId($batch_id, $student_id)
    {
        return Registration::whereHas('group.groupMember.student', function ($query) use ($student_id, $batch_id) {
            $query->where('id', $student_id);
        })->with('industry:id,name,address')->where('batch_id', $batch_id)->where('step', '!=' , '0')->first();
    }

    public function getAllHistoryRegistrationByStudentId($student_id)
    {
        return Registration::whereHas('group.groupMember.student', function ($query) use ($student_id) {
            $query->where('id', $student_id);
        })->with('industry:id,name,address')->paginate(5);
    }

    public function createRegistration(array $data)
    {
        return Registration::create($data);
    }

    public function updateRegistration($id, array $data)
    {
        return Registration::where('id', $id)->update($data);
    }

    public function updateStatusRegistration($id, $status)
    {
        // dd($id, $status);
        if ($status == 'accept') {
            return Registration::where('id', $id)->update(['status' => '1']);
        }else if ($status == 'reject') {
            return Registration::where('id', $id)->update(['status' => '2']);
        }
    }

    public function updateRegistrationStep($id, $step)
    {
        return Registration::where('id', $id)->update(['step' => $step]);
    }

    public function deleteRegistration($id)
    {
        return Registration::where('id', $id)->delete();
    }
}
