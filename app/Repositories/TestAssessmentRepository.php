<?php

namespace App\Repositories;

use App\Models\TestAssessment;

class TestAssessmentRepository
{
    /**
     * Update or create a test assessment record.
     *
     * @param array $data
     * @return \App\Models\TestAssessment
     */
    public function updateOrCreate(array $data)
    {
        return TestAssessment::updateOrCreate(
            [
                'assessment_id' => $data['assessment_id'],
            ],
            ['score' => $data['score']]
        );
    }

    /**
     * Check if the test assessment is complete (all scores are filled).
     *
     * @param int $assessment_id
     * @return bool
     */
    public function isTestAssessmentComplete($assessment_id)
    {
        // Count assessments with a null score for the given assessment ID
        $notComplete = TestAssessment::where('assessment_id', $assessment_id)->whereNull('score')->count();

        // If there are any incomplete assessments (null scores), it's not complete
        $isComplete = $notComplete > 0 ? false : true;
        return $isComplete;
    }
}
