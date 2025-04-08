<?php

namespace App\Repositories;

use App\Models\TestAssessment;

class TestAssessmentRepository
{
    public function updateOrCreate(array $data)
    {
        return TestAssessment::updateOrCreate(
            [
                'assessment_id' => $data['assessment_id'],
            ],
            ['score' => $data['score']]
        );
    }

    public function isTestAssessmentComplete($assessment_id){
        $notComplete = TestAssessment::where('assessment_id',$assessment_id)->whereNull('score')->count();

        $isComplete = $notComplete > 0 ? false : true;
        return $isComplete;
    }
}