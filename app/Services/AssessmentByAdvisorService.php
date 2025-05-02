<?php

namespace App\Services;

use App\Repositories\AssessmentByAdvisorRepository;

class AssessmentByAdvisorService
{
    protected $assessmentByAdvisorRepository;

    // Constructor Injection
    public function __construct(AssessmentByAdvisorRepository $assessmentByAdvisorRepository)
    {
        $this->assessmentByAdvisorRepository = $assessmentByAdvisorRepository;
    }

    /**
     * Retrieve assessment data for a specific advisor, with optional filters.
     * 
     * @param int $advisor_id
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAssessmentByAdvisor($advisor_id, $filters = [])
    {
        return $this->assessmentByAdvisorRepository->getAssessmentByAdvisor($advisor_id, $filters);
    }

    /**
     * Retrieve the count of students who have not been assessed by the advisor.
     * 
     * @param int $advisor_id
     * @return int
     */
    public function getNotAssessedCountByAdvisor($advisor_id, $batch_id)
    {
        return $this->assessmentByAdvisorRepository->countNotAssessedByAdvisor($advisor_id, $batch_id);
    }

    /**
     * Retrieve the count of students who have passed or not passed their assessment based on the final score.
     * 
     * @param int $advisor_id
     * @param string $status (either 'pass' or 'not pass')
     * @return int
     */
    public function getAssessedCountByAdvisor($advisor_id, $batch_id, $status)
    {
        // Fetch assessments for the advisor
        $data = $this->assessmentByAdvisorRepository->getAssessedByAdvisor($advisor_id, $batch_id);

        $countPass = 0;
        $countNotPass = 0;

        // Loop through each assessment
        foreach ($data as $dt) {

            // Initialize scores for each category
            $technical_score = 0;
            $non_technical_score = 0;
            $final_report_score = 0;

            // Calculate technical score average
            foreach ($dt->technical_assessment as $aspect_score) {
                $technical_score += $aspect_score->score ?? 0;
            }
            $technical_score_average = $technical_score / count($dt->technical_assessment);

            // Calculate non-technical score average
            foreach ($dt->non_technical_assessment as $aspect_score) {
                $non_technical_score += $aspect_score->score ?? 0;
            }
            $non_technical_score_average = $non_technical_score / count($dt->non_technical_assessment);

            // Calculate final report score average
            foreach ($dt->final_report_assessment as $aspect_score) {
                $final_report_score += $aspect_score->score ?? 0;
            }
            $final_report_score_average = $final_report_score / count($dt->final_report_assessment);

            // Calculate the final internship score
            $internship_score = round((($technical_score_average + $non_technical_score_average + $final_report_score_average + $dt->test_assessment->score) / 4), 2);

            // Count passing and failing students based on the internship score
            if ($internship_score >= 75) {
                $countPass += 1;
            } else {
                $countNotPass += 1;
            }
        }

        // Return the count based on the requested status (pass or not pass)
        return $status == 'pass' ? $countPass : $countNotPass;
    }
}
