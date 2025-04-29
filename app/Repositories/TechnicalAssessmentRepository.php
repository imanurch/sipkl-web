<?php

namespace App\Repositories;

use App\Models\TechnicalAssessment;
use PDO;

class TechnicalAssessmentRepository
{
    /**
     * Create a new technical assessment record.
     *
     * @param array $data
     * @return \App\Models\TechnicalAssessment
     */
    public function createTechnicalAssessment(array $data)
    {
        return TechnicalAssessment::create($data); 
    }

    /**
     * Delete a technical assessment by its ID.
     *
     * @param int $assessment_id
     * @return int
     */
    public function deleteTechnicalAssessment($assessment_id)
    {
        return TechnicalAssessment::where('assessment_id', $assessment_id)->delete();
    }

    /**
     * Check if the technical assessment is complete (all scores are filled).
     *
     * @param int $assessment_id
     * @return bool
     */
    public function isTechnicalAssessmentComplete($assessment_id)
    {
        // Count assessments with a null score for the given assessment ID
        $notComplete = TechnicalAssessment::where('assessment_id', $assessment_id)->whereNull('score')->count();

        // If there are any incomplete assessments (null scores), it's not complete
        $isComplete = $notComplete > 0 ? false : true;
        return $isComplete;
    }
}
