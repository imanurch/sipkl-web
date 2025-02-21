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
