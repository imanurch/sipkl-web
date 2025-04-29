<?php

namespace App\Repositories;

use App\Models\Student;

class SpecificStudentRepository
{
    /**
     * Get a list of students who have not registered for an internship in the specified batch and department.
     *
     * @param int $activeBatch_id
     * @param int $student_department
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getNonRegisteredInternList($activeBatch_id, $student_department)
    {
        // Retrieve students who are not registered for internships in the specified batch and department
        return Student::whereDoesntHave('groupMember.group.internship', function ($query) use ($activeBatch_id) {
            $query->where('batch_id', $activeBatch_id);
        })
            ->whereDoesntHave('groupMember.group.registration', function ($query) {
                $query->where('status', ['0', '1']); // Unconfirmed or accepted status
            })
            ->where('department_id', $student_department)
            ->select('id', 'name', 'nis')
            ->get();
    }

    /**
     * Get a list of distinct student years.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStudentYear()
    {
        // Retrieve distinct student years
        return Student::select('year')->distinct()->get();
    }

    /**
     * Get the most recent year in which students are enrolled.
     *
     * @return \App\Models\Student|null
     */
    public function getLastYearStudent()
    {
        // Retrieve the most recent student year
        return Student::select('year')->latest()->first();
    }

    /**
     * Find a student by their ID.
     *
     * @param int $id
     * @return \App\Models\Student|null
     */
    public function findStudentById($id)
    {
        // Retrieve a student by their ID
        return Student::find($id);
    }

    /**
     * Find a student by their user ID.
     *
     * @param int $user_id
     * @return \App\Models\Student|null
     */
    public function getStudentByUserId($user_id)
    {
        // Retrieve a student by their user ID
        return Student::where('user_id', $user_id)->first();
    }
}
