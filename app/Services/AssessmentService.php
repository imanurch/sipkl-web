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
}
