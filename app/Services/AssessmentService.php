<?php

namespace App\Services;

use App\Models\Assessment;
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
            $score = round((($dt->industry_score) + ($dt->advisor_score) + ($dt->final_test_score)) / 3, 2);

            // cek kelulusan
            if ($score >= 75) {
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

    public function getAssessmentByStudentIdAndInternshipId($student_id, $internship_id)
    {
        return $this->assessmentRepository->getAssessmentByStudentIdAndInternshipId($student_id, $internship_id);
    }

    public function addAssessment(array $data)
    {
        return $this->assessmentRepository->createAssessment($data);
    }

    public function updateScoreAssessment($id, array $data)
    {
        return $this->assessmentRepository->updateScoreAssessment($id, $data);
    }
}
