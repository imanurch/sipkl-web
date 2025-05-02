<?php

namespace App\Repositories;

use App\Models\Registration;

class StudentRegistrationRepository
{
    /**
     * Get a specific registration for a student by student ID and batch ID.
     * 
     * @param int $batch_id
     * @param int $student_id
     * @return \App\Models\Registration|null
     */
    public function getRegistrationByStudentId($batch_id, $student_id)
    {
        return Registration::whereHas('group.groupMember.student', function ($query) use ($student_id, $batch_id) {
            $query->where('id', $student_id);
        })->with('industry:id,name,address')
            ->where('batch_id', $batch_id)
            ->where('step', '!=', '0')
            ->first();
    }

    /**
     * Get all registration history for a student.
     * 
     * @param int $student_id
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllHistoryRegistrationByStudentId($student_id)
    {
        return Registration::whereHas('group.groupMember.student', function ($query) use ($student_id) {
            $query->where('id', $student_id);
        })->with('industry:id,name,address')->paginate(5);
    }

    /**
     * Update the registration step.
     * 
     * @param int $id
     * @param string $step
     * @return int
     */
    public function updateRegistrationStep($id, $step)
    {
        return Registration::where('id', $id)->update(['step' => $step]);
    }

    /**
     * Create a new registration record.
     * 
     * @param array $data
     * @return \App\Models\Registration
     */
    public function createRegistration(array $data)
    {
        return Registration::create($data);
    }
}
