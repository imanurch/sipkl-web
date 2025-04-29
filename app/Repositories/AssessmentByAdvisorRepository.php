<?php

namespace App\Repositories;

use App\Models\Assessment;

class AssessmentByAdvisorRepository
{
    /**
     * Get filtered assessment data for a specific advisor.
     *
     * @param int $advisor_id
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAssessmentByAdvisor($advisor_id, $filters = [])
    {
        $query = Assessment::query();

        // Filter by batch ID
        if ($filters['batch_id'] != null) {
            $query->whereHas('internship', function ($subQuery) use ($filters) {
                $subQuery->where('batch_id', $filters['batch_id']);
            });
        }

        // Filter by search keyword (student name or industry name)
        if ($filters['search'] != null) {
            $query->whereHas('student', function ($subQuery) use ($filters) {
                $subQuery->where('name', 'like', '%' . $filters['search'] . '%');
            })->orWhereHas('internship.industry', function ($subQuery) use ($filters) {
                $subQuery->where('name', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Get assessments by advisor with necessary relationships
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

    /**
     * Count total unassessed students by advisor.
     *
     * @param int $advisor_id
     * @return int
     */
    public function countNotAssessedByAdvisor($advisor_id)
    {
        return Assessment::whereHas('internship', function ($query) use ($advisor_id) {
            $query->where('advisor_id', $advisor_id);
        })
            ->where(function ($query) {
                $query->whereDoesntHave('technical_assessment')
                    ->orWhereDoesntHave('non_technical_assessment')
                    ->orWhereDoesntHave('final_report_assessment')
                    ->orWhereDoesntHave('test_assessment')
                    ->orWhereHas('non_technical_assessment', function ($q) {
                        $q->whereNull('score');
                    })
                    ->orWhereHas('final_report_assessment', function ($q) {
                        $q->whereNull('score');
                    });
            })
            ->count();
    }

    /**
     * Get all fully assessed students by advisor.
     *
     * @param int $advisor_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAssessedByAdvisor($advisor_id)
    {
        return Assessment::whereHas('internship', function ($query) use ($advisor_id) {
            $query->where('advisor_id', $advisor_id);
        })
            ->whereHas('technical_assessment')
            ->whereHas('non_technical_assessment', function ($query) {
                $query->whereNotNull('score');
            })
            ->whereHas('final_report_assessment', function ($query) {
                $query->whereNotNull('score');
            })
            ->whereHas('test_assessment')
            ->get();
    }
}
