<?php

namespace App\Repositories;

use App\Models\FinalReportAssessment;

class FinalReportAssessmentRepository
{
    /**
     * Create or update a final report assessment record.
     *
     * @param  array  $data
     * @return \App\Models\FinalReportAssessment
     */
    public function updateOrCreate(array $data)
    {
        return FinalReportAssessment::updateOrCreate(
            [
                'assessment_id' => $data['assessment_id'],
                'aspect' => $data['aspect'],
            ],
            ['score' => $data['score']]
        );
    }

    /**
     * Check if all final report assessments are completed for given assessment ID.
     *
     * @param  int  $assessment_id
     * @return bool
     */
    public function isFinalReportAssessmentComplete($assessment_id)
    {
        $notComplete = FinalReportAssessment::where('assessment_id', $assessment_id)
            ->whereNull('score')
            ->count();

        return $notComplete === 0;
    }
}
