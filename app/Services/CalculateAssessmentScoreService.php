<?php

namespace App\Services;

use App\Repositories\TestAssessmentRepository;
use App\Repositories\TechnicalAssessmentRepository;
use App\Repositories\FinalReportAssessmentRepository;
use App\Repositories\NonTechnicalAssessmentRepository;

class CalculateAssessmentScoreService
{
    protected $nonTechnicalAssessmentRepository,
        $finalReportAssessmentRepository,
        $technicalAssessmentRepository,
        $testAssessmentRepository;

    // Constructor Injection
    public function __construct(
        NonTechnicalAssessmentRepository $nonTechnicalAssessmentRepository,
        FinalReportAssessmentRepository $finalReportAssessmentRepository,
        TechnicalAssessmentRepository $technicalAssessmentRepository,
        TestAssessmentRepository $testAssessmentRepository
    ) {
        $this->nonTechnicalAssessmentRepository = $nonTechnicalAssessmentRepository;
        $this->finalReportAssessmentRepository = $finalReportAssessmentRepository;
        $this->technicalAssessmentRepository = $technicalAssessmentRepository;
        $this->testAssessmentRepository = $testAssessmentRepository;
    }

    /**
     * Calculate the average score of the technical assessment.
     *
     * @param object $data
     * @return float
     */
    public function calculateTechnicalScore($data)
    {
        $technical_score = 0;
        $technical_score_average = 0;
        $technical_aspect = [];
        $technical_aspect_score = [];

        // Summing the technical aspect scores
        foreach ($data->technical_assessment as $aspect_score) {
            $technical_score += $aspect_score->score;
            $technical_aspect[] = $aspect_score->aspect;
            $technical_aspect_score[] = $aspect_score->score;
        }
        
        // Calculate the average score
        $technical_score_average = $technical_score / count($data->technical_assessment);
        $data->technical_aspect = $technical_aspect;
        $data->technical_aspect_score = $technical_aspect_score;
        $data->technical_score_average = $technical_score_average;

        return $technical_score_average;
    }

    /**
     * Calculate the average score of the non-technical assessment.
     *
     * @param object $data
     * @return float
     */
    public function calculateNonTechnicalScore($data)
    {
        $non_technical_score = 0;
        $non_technical_score_average = 0;

        // Summing the non-technical aspect scores
        foreach ($data->non_technical_assessment as $aspect_score) {
            $non_technical_score += $aspect_score->score;
            $aspect = str_replace(' ', '_', $aspect_score->aspect);
            $data->$aspect = $aspect_score->score;
        }

        // If the non-technical assessment is complete, calculate the average score
        if ($this->nonTechnicalAssessmentRepository->isNonTechnicalAssessmentComplete($data->id)) {
            $non_technical_score_average = ($non_technical_score / count($data->non_technical_assessment));
            $data->non_technical_score_average = $non_technical_score_average;
        }

        return $non_technical_score_average;
    }

    /**
     * Calculate the average score of the final report assessment.
     *
     * @param object $data
     * @return float
     */
    public function calculateFinalReportScore($data)
    {
        $final_report_score = 0;
        $final_report_score_average = 0;

        // Summing the final report aspect scores
        foreach ($data->final_report_assessment as $aspect_score) {
            $final_report_score += $aspect_score->score;
            $aspect = str_replace(' ', '_', $aspect_score->aspect);
            $data->$aspect = $aspect_score->score;
        }

        // If the final report assessment is complete, calculate the average score
        if ($this->finalReportAssessmentRepository->isFinalReportAssessmentComplete($data->id)) {
            $final_report_score_average = $final_report_score / count($data->final_report_assessment);
            $data->final_report_score_average = $final_report_score_average;
        }

        return $final_report_score_average;
    }

    /**
     * Calculate the final internship score based on all assessments.
     *
     * @param object $data
     */
    public function calculateInternshipScore($data)
    {
        $technical_score_average = 0;
        $non_technical_score_average = 0;
        $final_report_score_average = 0;

        // Calculate technical score if technical assessment exists
        if (count($data->technical_assessment) > 0) {
            $technical_score_average = $this->calculateTechnicalScore($data);
        }

        // Calculate non-technical score if non-technical assessment exists
        if (count($data->non_technical_assessment) > 0) {
            $non_technical_score_average = $this->calculateNonTechnicalScore($data);
        }

        // Calculate final report score if final report assessment exists
        if (count($data->final_report_assessment) > 0) {
            $final_report_score_average = $this->calculateFinalReportScore($data);
        }

        // Calculate the final internship score only if all assessments are complete
        if (
            count($data->technical_assessment) > 0 &&
            $this->technicalAssessmentRepository->isTechnicalAssessmentComplete($data->id) &&
            $this->nonTechnicalAssessmentRepository->isNonTechnicalAssessmentComplete($data->id) &&
            $this->finalReportAssessmentRepository->isFinalReportAssessmentComplete($data->id) &&
            $this->testAssessmentRepository->isTestAssessmentComplete($data->id)
        ) {
            $data->internship_score = round((
                ($technical_score_average + $non_technical_score_average + $final_report_score_average + $data->test_assessment->score) / 4
            ), 2);
            // Set internship status based on the score
            $data->internship_status = $data->internship_score >= 75 ? 'Lulus' : 'Tidak Lulus';
        }
    }
}
