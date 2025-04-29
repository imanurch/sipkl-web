<?php

namespace App\Repositories;

use App\Models\NonTechnicalAssessment;

class NonTechnicalAssessmentRepository
{
    /**
     * Update or create a non-technical assessment.
     *
     * @param array $data
     * @return \App\Models\NonTechnicalAssessment
     */
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

    /**
     * Check if all non-technical assessments are completed for a given assessment ID.
     *
     * @param int $assessment_id
     * @return bool
     */
    public function isNonTechnicalAssessmentComplete($assessment_id)
    {
        $notComplete = NonTechnicalAssessment::where('assessment_id', $assessment_id)
            ->whereNull('score')
            ->count();

        return $notComplete === 0;
    }
}
