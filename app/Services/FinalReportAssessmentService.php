<?php

namespace App\Services;

use App\Repositories\FinalReportAssessmentRepository;

class FinalReportAssessmentService
{
    protected $finalReportAssessmentRepository;

    // Constructor Injection
    public function __construct(FinalReportAssessmentRepository $finalReportAssessmentRepository)
    {
        $this->finalReportAssessmentRepository = $finalReportAssessmentRepository;
    }

    public function updateOrCreate($data)
    {
        return $this->finalReportAssessmentRepository->updateOrCreate($data);
    }

    public function isFinalReportAssessmentComplete($assessment_id)
    {
        return $this->finalReportAssessmentRepository->isFinalReportAssessmentComplete($assessment_id);
    }
}
