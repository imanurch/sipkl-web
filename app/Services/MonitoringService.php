<?php

namespace App\Services;

use App\Helpers\DateFormatHelper;
use App\Repositories\MonitoringRepository;

class MonitoringService
{
    protected $monitoringRepository;

    // Constructor Injection
    public function __construct(MonitoringRepository $monitoringRepository)
    {
        $this->monitoringRepository = $monitoringRepository;
    }

    public function getMonitoring($batch_id, $filters)
    {
        $data = $this->monitoringRepository->getMonitoring($batch_id, $filters);
        foreach ($data as $dt) {
            $dt->date = DateFormatHelper::dateFormat($dt->date);
        }
        return $data;
    }

    public function getMonitoringByAdvisorIdAndBatch($advisor_id, $batch_id, $filters)
    {
        $data = $this->monitoringRepository->getMonitoringByAdvisorIdAndBatch($advisor_id, $batch_id, $filters);
        foreach ($data as $dt) {
            $dt->date = DateFormatHelper::dateFormat($dt->date);
        }
        return $data;
    }

    public function getById($id)
    {
        return $this->monitoringRepository->findById($id);
    }

    public function addMonitoring($data)
    {
        return $this->monitoringRepository->createMonitoring($data);
    }

    public function updateMonitoring($id, $data)
    {
        return $this->monitoringRepository->updateMonitoring($id, $data);
    }

    public function deleteMonitoring($id)
    {
        return $this->monitoringRepository->deleteMonitoring($id);
    }
}
