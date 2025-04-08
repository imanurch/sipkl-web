<?php

namespace App\Services;

use App\Repositories\TestAssessmentRepository;

class TestAssessmentService
{
    protected $testAssessmentRepository;

    // Constructor Injection
    public function __construct(TestAssessmentRepository $testAssessmentRepository)
    {
        $this->testAssessmentRepository = $testAssessmentRepository;
    }

    public function updateOrCreate($data)
    {
        return $this->testAssessmentRepository->updateOrCreate($data);
    }

    public function isTestAssessmentComplete($assessment_id)
    {
        return $this->testAssessmentRepository->isTestAssessmentComplete($assessment_id);
    }
}
