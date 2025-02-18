<?php

namespace App\Services;

use App\Repositories\MonitoringRepository;

class MonitoringService
{
    protected $monitoringRepository;

    // Constructor Injection
    public function __construct(MonitoringRepository $monitoringRepository)
    {
        $this->monitoringRepository = $monitoringRepository;
    }

    public function getMonitoringByAdvisorIdAndBatch($advisor_id, $batch_id, $filters)
    {
        return $this->monitoringRepository->getMonitoringByAdvisorIdAndBatch($advisor_id, $batch_id, $filters);
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
