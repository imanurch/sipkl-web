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
        // Get the registration for a student in the given batch, excluding step '0'
        return Registration::whereHas('group.groupMember.student', function ($query) use ($student_id, $batch_id) {
            $query->where('id', $student_id);
        })->with('industry:id,name,address') // Load related industry info
            ->where('batch_id', $batch_id)
            ->where('step', '!=', '0') // Exclude registrations where step is '0'
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
        // Get all historical registrations for the student, paginated
        return Registration::whereHas('group.groupMember.student', function ($query) use ($student_id) {
            $query->where('id', $student_id);
        })->with('industry:id,name,address') // Load related industry info
            ->paginate(5); // Paginate results, 5 per page
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
        // Update the 'step' of the registration by ID
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
        // Create a new registration record with the given data
        return Registration::create($data);
    }
}
