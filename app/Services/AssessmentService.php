<?php

namespace App\Services;

use App\Repositories\AssessmentRepository;

class AssessmentService
{
    protected $assessmentRepository,
    $calculateAssessmentScoreService;

    // Constructor Injection
    public function __construct(
        AssessmentRepository $assessmentRepository,
        CalculateAssessmentScoreService $calculateAssessmentScoreService
    ) {
        $this->assessmentRepository = $assessmentRepository;
        $this->calculateAssessmentScoreService = $calculateAssessmentScoreService;
    }

    /**
     * Retrieve assessment data with optional filters.
     * 
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAssessment($filters = [])
    {
        return $this->assessmentRepository->getAssessment($filters);
    }

    /**
     * Retrieve the count of students who have not been assessed.
     * 
     * @return int
     */
    public function getNotAssessedCount()
    {
        return $this->assessmentRepository->countNotAssessed();
    }

    /**
     * Retrieve the count of students who have passed or failed based on the final score.
     * 
     * @param string $status (either 'pass' or 'not pass')
     * @return int
     */
    public function getAssessedCount($status)
    {
        // Fetch all assessments
        $data = $this->assessmentRepository->getAssessed();

        $countPass = 0;
        $countNotPass = 0;

        // Loop through each assessment
        foreach ($data as $dt) {
            $this->calculateAssessmentScoreService->calculateInternshipScore($dt);

            // Count passing and failing students based on the internship score
            if ($dt->internship_status == 'Lulus') {
                $countPass += 1;
            } else {
                $countNotPass += 1;
            }
        }

        // Return the count based on the requested status (pass or not pass)
        return $status == 'pass' ? $countPass : $countNotPass;
    }

    /**
     * Retrieve assessment data for a specific student and internship.
     * 
     * @param int $student_id
     * @param int $internship_id
     * @return \App\Models\Assessment
     */
    public function getAssessmentByStudentIdAndInternshipId($student_id, $internship_id)
    {
        return $this->assessmentRepository->getAssessmentByStudentIdAndInternshipId($student_id, $internship_id);
    }

    /**
     * Add a new assessment to the system.
     * 
     * @param array $data
     * @return \App\Models\Assessment
     */
    public function addAssessment(array $data)
    {
        return $this->assessmentRepository->createAssessment($data);
    }

    /**
     * Retrieve assessments for a specific batch.
     * 
     * @param int $batch_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAssessmentByBatch($batch_id)
    {
        return $this->assessmentRepository->getAssessmentByBatch($batch_id);
    }


}
