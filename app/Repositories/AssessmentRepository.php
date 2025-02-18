<?php

namespace App\Repositories;

use App\Models\Assessment;

class AssessmentRepository
{
    public function getAssessment($filters = [])
    {
        $query = Assessment::query();

        // filter batch
        if ($filters['batch_id'] != null) {
            $query->whereHas('internship', function ($subQuery) use ($filters) {
                $subQuery->where('batch_id', $filters['batch_id']);
            });
        };

        // filter search
        if ($filters['search'] != null) {
            $query->whereHas('student',function ($subQuery) use ($filters) {
                $subQuery->where('name', 'like', '%' . $filters['search'] . '%');
            });
        };

        return $query->with(
            'student:id,name,department_id',
            'student.department:id,name',
            'internship.industry:id,name',
            'internship.internDocument'
        )->paginate(5);
    }

    // public function getAssessmentByAdvisor($advisor_id)
    // {
    //     return Assessment::whereHas('internships', function ($query) use ($advisor_id) {
    //         $query->where('advisor_id', $advisor_id);
    //     })->with(
    //         'students:id,name',
    //         'internships.industries:id,name',
    //         'internships.internDocument'
    //     )->get();
    // }

    // public function getAssessmentByBatchAndAdvisor($batch_id, $advisor_id)
    // {
    //     return Assessment::whereHas('internships', function ($query) use ($batch_id, $advisor_id) {
    //         $query->where('advisor_id', $advisor_id)->where('batch_id', $batch_id);
    //     })->with(
    //         'students:id,name',
    //         'internships.industries:id,name',
    //         'internships.internDocument'
    //     )->get();
    // }

    public function getAssessmentByStudentIdAndInternshipId($student_id, $internship_id)
    {
        return Assessment::where('student_id', $student_id)->where('internship_id', $internship_id)->first();
    }

    public function createAssessment(array $data)
    {
        return Assessment::create($data);
    }

    public function updateScoreAssessment($id, array $data)
    {
        return Assessment::where('id', $id)->update($data);
    }

    // public function deleteAssessment($id)
    // {
    //     return Assessment::where('id', $id)->delete();
    // }
}
