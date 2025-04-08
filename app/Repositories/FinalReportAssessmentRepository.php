<?php

namespace App\Repositories;

use App\Models\FinalReportAssessment;

class FinalReportAssessmentRepository
{
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

    public function isFinalReportAssessmentComplete($assessment_id){
        $notComplete = FinalReportAssessment::where('assessment_id',$assessment_id)->whereNull('score')->count();

        $isComplete = $notComplete > 0 ? false : true;
        return $isComplete;
    }
}