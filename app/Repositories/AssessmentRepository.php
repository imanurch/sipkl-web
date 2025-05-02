<?php

namespace App\Repositories;

use App\Models\Assessment;

class AssessmentRepository
{
    /**
     * Get paginated assessments based on filters.
     *
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAssessment($filters = [])
    {
        $query = Assessment::query();

        // Filter by batch
        if ($filters['batch_id'] != null) {
            $query->whereHas('internship', function ($subQuery) use ($filters) {
                $subQuery->where('batch_id', $filters['batch_id']);
            });
        }

        // Filter by search (student name or industry name)
        if ($filters['search'] != null) {
            $query->whereHas('student', function ($subQuery) use ($filters) {
                $subQuery->where('name', 'like', '%' . $filters['search'] . '%');
            })->orWhereHas('internship.industry', function ($subQuery) use ($filters) {
                $subQuery->where('name', 'like', '%' . $filters['search'] . '%');
            });
        }

        // Include relationships and paginate
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

    /**
     * Count students who are not fully assessed.
     *
     * @return int
     */
    public function countNotAssessed($batch_id)
    {
        return Assessment::whereHas('internship', function ($query) use ($batch_id) {
            $query->where('batch_id', $batch_id);
        })->where(function ($query) {
            $query->whereDoesntHave('technical_assessment')
                ->orWhereDoesntHave('non_technical_assessment')
                ->orWhereDoesntHave('final_report_assessment')
                ->orWhereDoesntHave('test_assessment')
                ->orWhereHas('non_technical_assessment', function ($query) {
                    $query->whereNull('score');
                })
                ->orWhereHas('final_report_assessment', function ($query) {
                    $query->whereNull('score');
                });
        })->count();
    }

    /**
     * Get all fully assessed students.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAssessed($batch_id)
    {
        return Assessment::whereHas('internship', function ($query) use ($batch_id) {
            $query->where('batch_id', $batch_id);
        })->whereHas('technical_assessment')
            ->whereHas('non_technical_assessment', function ($query) {
                $query->whereNotNull('score');
            })
            ->whereHas('final_report_assessment', function ($query) {
                $query->whereNotNull('score');
            })
            ->whereHas('test_assessment')->get();
    }

    /**
     * Get a specific assessment based on student and internship IDs.
     *
     * @param int $student_id
     * @param int $internship_id
     * @return \App\Models\Assessment|null
     */
    public function getAssessmentByStudentIdAndInternshipId($student_id, $internship_id)
    {
        return Assessment::where('student_id', $student_id)
            ->where('internship_id', $internship_id)
            ->first();
    }

    /**
     * Store a new assessment record.
     *
     * @param array $data
     * @return \App\Models\Assessment
     */
    public function createAssessment(array $data)
    {
        return Assessment::create($data);
    }

    /**
     * Get all assessments by batch ID.
     *
     * @param int $batch_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAssessmentByBatch($batch_id)
    {
        return Assessment::whereHas('internship', function ($query) use ($batch_id) {
            $query->where('batch_id', $batch_id);
        })->get();
    }
}
