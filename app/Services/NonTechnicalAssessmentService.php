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

    public function updateOrCreate($id, $data)
    {
        $nonTechnicalAspects = [
            'Kedisiplinan' => $data->dicipline,
            'Kerja Sama' => $data->teamwork,
            'Inisiatif' => $data->initiative,
            'Tanggung Jawab' => $data->responsibility,
            'Jujur dan Santun' => $data->honest,
        ];

        foreach ($nonTechnicalAspects as $aspect => $score) {
            $this->nonTechnicalAssessmentRepository->updateOrCreate([
                'assessment_id' => $id,
                'aspect' => $aspect,
                'score' => $score,
            ]);
        }
    }

    public function isNonTechnicalAssessmentComplete($assessment_id)
    {
        return $this->nonTechnicalAssessmentRepository->isNonTechnicalAssessmentComplete($assessment_id);
    }
}
