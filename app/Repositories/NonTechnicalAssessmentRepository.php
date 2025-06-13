<?php

namespace App\Repositories;

use App\Models\NonTechnicalAssessment;

class NonTechnicalAssessmentRepository
{
    public function updateOrCreate(array $data)
    {
        return NonTechnicalAssessment::updateOrCreate(
            [
                'assessment_id' => $data['assessment_id'],
                'aspect' => $data['aspect'],
            ],
            [
                'score' => $data['score'],
            ]
        );
    }

    public function isNonTechnicalAssessmentComplete($assessment_id)
    {
        $notComplete = NonTechnicalAssessment::where('assessment_id', $assessment_id)
            ->whereNull('score')
            ->count();

        return $notComplete === 0;
    }
}
