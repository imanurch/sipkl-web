<?php

namespace App\Repositories;

use App\Models\TechnicalAssessment;
use PDO;

class TechnicalAssessmentRepository
{
    public function createTechnicalAssessment(array $data)
    {
        return TechnicalAssessment::create($data);
    }

    public function deleteTechnicalAssessment($assessment_id)
    {
        return TechnicalAssessment::where('assessment_id', $assessment_id)->delete();
    }

    public function isTechnicalAssessmentComplete($assessment_id){
        $notComplete = TechnicalAssessment::where('assessment_id',$assessment_id)->whereNull('score')->count();

        $isComplete = $notComplete > 0 ? false : true;
        return $isComplete;
    }

}