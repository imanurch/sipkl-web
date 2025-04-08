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
            $query->whereHas('student', function ($subQuery) use ($filters) {
                $subQuery->where('name', 'like', '%' . $filters['search'] . '%');
            })->orWhereHas('internship.industry', function ($subQuery) use ($filters) {
                $subQuery->where('name', 'like', '%' . $filters['search'] . '%');
            });
        };

        return $query->with(
            'student:id,name,department_id',
            'student.department:id,name',
            'internship.industry:id,name',
            'internship.internDocument',
            'technical_assessment',
            'non_technical_assessment',
            'final_report_assessment',
            'test_assessment',
        )->paginate(5);
    }

    public function countNotAssessed()
    {
        // return Assessment::whereNull('industry_score')->orWhereNull('advisor_score')->orWhereNull('final_test_score')->count();
        return Assessment::whereDoesntHave('technical_assessment')->orWhereDoesntHave('non_technical_assessment')->orWhereDoesntHave('final_report_assessment')->orWhereDoesntHave('test_assessment')->orWhereHas('non_technical_assessment', function ($query) {
            $query->whereNull('score');
        })->orWhereHas('final_report_assessment', function ($query) {
            $query->whereNull('score');
        })->count();
    }

    public function getAssessed()
    {
        // return Assessment::whereNotNull('industry_score')->orWhereNotNull('advisor_score')->orWhereNotNull('final_test_score')->get();
        return Assessment::whereHas('technical_assessment')->WhereHas('non_technical_assessment', function ($query) {
            $query->whereNotNull('score');
        })->WhereHas('final_report_assessment', function ($query) {
            $query->whereNotNull('score');
        })->WhereHas('test_assessment')->get();
    }

    public function getAssessmentByAdvisor($advisor_id, $filters = [])
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
            $query->whereHas('student', function ($subQuery) use ($filters) {
                $subQuery->where('name', 'like', '%' . $filters['search'] . '%');
            })->orWhereHas('internship.industry', function ($subQuery) use ($filters) {
                $subQuery->where('name', 'like', '%' . $filters['search'] . '%');
            });
        };

        return $query->whereHas('internship', function ($query) use ($advisor_id) {
            $query->where('advisor_id', $advisor_id);
        })->with(
            'student:id,name,department_id',
            'student.department:id,name',
            'internship.industry:id,name',
            'internship.internDocument',
            'technical_assessment',
            'non_technical_assessment',
            'final_report_assessment',
            'test_assessment',
        )->paginate(5);
    }

    public function countNotAssessedByAdvisor($advisor_id)
    {
        // return Assessment::whereNull('industry_score')->orWhereNull('advisor_score')->orWhereNull('final_test_score')->count();
        return Assessment::whereHas('internship', function ($query) use ($advisor_id) {
            $query->where('advisor_id', $advisor_id);
        })->whereDoesntHave('technical_assessment')->orWhereDoesntHave('non_technical_assessment')->orWhereDoesntHave('final_report_assessment')->orWhereDoesntHave('test_assessment')->orWhereHas('non_technical_assessment', function ($query) {
            $query->whereNull('score');
        })->orWhereHas('final_report_assessment', function ($query) {
            $query->whereNull('score');
        })->count();
    }

    public function getAssessedByAdvisor($advisor_id)
    {
        // return Assessment::whereNotNull('industry_score')->orWhereNotNull('advisor_score')->orWhereNotNull('final_test_score')->get();
        return Assessment::whereHas('internship', function ($query) use ($advisor_id) {
            $query->where('advisor_id', $advisor_id);
        })->whereHas('technical_assessment')->WhereHas('non_technical_assessment', function ($query) {
            $query->whereNotNull('score');
        })->WhereHas('final_report_assessment', function ($query) {
            $query->whereNotNull('score');
        })->WhereHas('test_assessment')->get();
    }

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

    // public function updateScoreAssessment($id, array $data)
    // {
    //     return Assessment::where('id', $id)->update($data);
    // }

    // public function deleteAssessment($id)
    // {
    //     return Assessment::where('id', $id)->delete();
    // }

    public function getAssessmentByBatch($batch_id)
    {
        return Assessment::whereHas('internship', function ($query) use ($batch_id) {
            $query->where('batch_id', $batch_id);
        })->get();
    }
}
