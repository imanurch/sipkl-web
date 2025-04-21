<?php

namespace App\Services;

use App\Repositories\AssessmentRepository;

class AssessmentService
{
    protected $assessmentRepository;

    // Constructor Injection
    public function __construct(AssessmentRepository $assessmentRepository)
    {
        $this->assessmentRepository = $assessmentRepository;
    }

    public function getAssessment($filters = [])
    {
        return $this->assessmentRepository->getAssessment($filters);
    }

    public function getNotAssessedCount()
    {
        return $this->assessmentRepository->countNotAssessed();
    }

    public function getAssessedCount($status)
    {
        $data = $this->assessmentRepository->getAssessed();

        $countPass = 0;
        $countNotPass = 0;
        foreach ($data as $dt) {

            $technical_score = 0;
            $non_technical_score = 0;
            $final_report_score = 0;

            // technical
            foreach ($dt->technical_assessment as $aspect_score) {
                $technical_score += $aspect_score->score;
            }
            $technical_score_average = $technical_score / count($dt->technical_assessment);

            // non technical
            foreach ($dt->non_technical_assessment as $aspect_score) {
                $non_technical_score += $aspect_score->score;
            }
            $non_technical_score_average = ($non_technical_score / count($dt->non_technical_assessment));

            // final report
            foreach ($dt->final_report_assessment as $aspect_score) {
                $final_report_score += $aspect_score->score;
            }
            $final_report_score_average = $final_report_score / count($dt->final_report_assessment);

            // final score
            $internship_score = round((($technical_score_average + $non_technical_score_average + $final_report_score_average + $dt->test_assessment->score) / 4), 2);

            if ($internship_score >= 75) {
                $countPass += 1;
            } else {
                $countNotPass += 1;
            }
        }

        if ($status == 'pass') {
            return $countPass;
        } else {
            return $countNotPass;
        }
    }

    public function getAssessmentByAdvisor($advisor_id, $filters = [])
    {
        return $this->assessmentRepository->getAssessmentByAdvisor($advisor_id, $filters);
    }

    public function getNotAssessedCountByAdvisor($advisor_id)
    {
        return $this->assessmentRepository->countNotAssessedByAdvisor($advisor_id);
    }

    public function getAssessedCountByAdvisor($advisor_id, $status)
    {
        $data = $this->assessmentRepository->getAssessedByAdvisor($advisor_id);

        $countPass = 0;
        $countNotPass = 0;
        foreach ($data as $dt) {

            $technical_score = 0;
            $non_technical_score = 0;
            $final_report_score = 0;

            // technical
            foreach ($dt->technical_assessment as $aspect_score) {
                $technical_score += $aspect_score->score;
            }
            $technical_score_average = $technical_score / count($dt->technical_assessment);

            // non technical
            foreach ($dt->non_technical_assessment as $aspect_score) {
                $non_technical_score += $aspect_score->score;
            }
            $non_technical_score_average = ($non_technical_score / count($dt->non_technical_assessment));

            // final report
            foreach ($dt->final_report_assessment as $aspect_score) {
                $final_report_score += $aspect_score->score;
            }
            $final_report_score_average = $final_report_score / count($dt->final_report_assessment);

            // final score
            $internship_score = round((($technical_score_average + $non_technical_score_average + $final_report_score_average + $dt->test_assessment->score) / 4), 2);

            if ($internship_score >= 75) {
                $countPass += 1;
            } else {
                $countNotPass += 1;
            }
        }

        if ($status == 'pass') {
            return $countPass;
        } else {
            return $countNotPass;
        }
    }

    public function getAssessmentByStudentIdAndInternshipId($student_id, $internship_id)
    {
        return $this->assessmentRepository->getAssessmentByStudentIdAndInternshipId($student_id, $internship_id);
    }

    public function addAssessment(array $data)
    {
        return $this->assessmentRepository->createAssessment($data);
    }

    public function getAssessmentByBatch($batch_id)
    {
        return $this->assessmentRepository->getAssessmentByBatch($batch_id);
    }
}
