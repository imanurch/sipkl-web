<?php

namespace App\Repositories;

use App\Models\Assessment;

class AssessmentRepository
{
    public function getAssessment($filters = [])
    {
        return Assessment::with(
            'students:id,name',
            'internships.industries:id,name',
            'internships.internDocument'
        )->paginate(5);

        // filter batch
        if ($filters['batch_id'] != null) {
            $query->where('batch_id', $filters['batch_id']);
        };

        // filter search
        if ($searchFilter != null) {
            $query->where('username', 'like', '%' . $searchFilter . '%')
                ->orWhere('email', 'like', '%' . $searchFilter . '%');
        };
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

    // public function countUnconfirmedAssessment($batch_id)
    // {
    //     return Assessment::where('status', '0')->where('batch_id', $batch_id)->count();
    // }

    // public function countAcceptedAssessment($batch_id)
    // {
    //     return Assessment::where('status', '1')->where('batch_id', $batch_id)->count();
    // }

    // public function countRejectedAssessment($batch_id)
    // {
    //     return Assessment::where('status', '2')->where('batch_id', $batch_id)->count();
    // }

    // public function getAssessmentByStudentIdAndInternshipId($studentId, $internship_id)
    // {
    //     return Assessment::where('studentId', $studentId)->where('internship_id', $internship_id)->first();
    // }

    // public function createAssessment(array $data)
    // {
    //     return Assessment::create($data);
    // }

    // public function updateScoreAssessment($studentId, $internship_id, array $data)
    // {
    //     return Assessment::where('studentId', $studentId)->where('internship_id', $internship_id)->update($data);
    // }

    // public function deleteAssessment($id)
    // {
    //     return Assessment::where('id', $id)->delete();
    // }
}
