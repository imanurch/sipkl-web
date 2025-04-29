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

    public function updateOrCreate($id, $data)
    {
        $finalReportAspects = [
            'Sikap' => $data->attitude,
            'Tata Tulis' => $data->writing,
            'Ketepatan Waktu' => $data->on_time,
            'Ketertiban' => $data->orderly,
            'Keseluruhan Laporan' => $data->final_report,
        ];

        foreach ($finalReportAspects as $aspect => $score) {
            $this->finalReportAssessmentRepository->updateOrCreate([
                'assessment_id' => $id,
                'aspect' => $aspect,
                'score' => $score,
            ]);
        }
    }

    public function isFinalReportAssessmentComplete($assessment_id)
    {
        return $this->finalReportAssessmentRepository->isFinalReportAssessmentComplete($assessment_id);
    }
}
