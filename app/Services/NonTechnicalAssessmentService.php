<?php

namespace App\Services;

use App\Repositories\NonTechnicalAssessmentRepository;

class NonTechnicalAssessmentService
{
    protected $nonTechnicalAssessmentRepository;

    // Constructor Injection
    public function __construct(NonTechnicalAssessmentRepository $nonTechnicalAssessmentRepository)
    {
        $this->nonTechnicalAssessmentRepository = $nonTechnicalAssessmentRepository;
    }

    public function updateOrCreate($data)
    {
        return $this->nonTechnicalAssessmentRepository->updateOrCreate($data);
    }

    public function isNonTechnicalAssessmentComplete($assessment_id)
    {
        return $this->nonTechnicalAssessmentRepository->isNonTechnicalAssessmentComplete($assessment_id);
    }
}
