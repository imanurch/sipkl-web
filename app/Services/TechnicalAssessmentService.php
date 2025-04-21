<?php

namespace App\Services;

use App\Repositories\TechnicalAssessmentRepository;

class TechnicalAssessmentService
{
    protected $technicalAssessmentRepository;

    // Constructor Injection
    public function __construct(TechnicalAssessmentRepository $technicalAssessmentRepository)
    {
        $this->technicalAssessmentRepository = $technicalAssessmentRepository;
    }

    public function addTechnicalAssessment($data)
    {
        return $this->technicalAssessmentRepository->createTechnicalAssessment($data);
    }

    public function deleteTechnicalAssessment($assessment_id)
    {
        return $this->technicalAssessmentRepository->deleteTechnicalAssessment($assessment_id);
    }

    public function isTechnicalAssessmentComplete($assessment_id)
    {
        return $this->technicalAssessmentRepository->isTechnicalAssessmentComplete($assessment_id);
    }
}
