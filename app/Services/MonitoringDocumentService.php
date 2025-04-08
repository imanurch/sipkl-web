<?php

namespace App\Services;

use App\Repositories\MonitoringDocumentRepository;

class MonitoringDocumentService
{
    protected $monitoringDocumentRepository;

    // Constructor Injection
    public function __construct(MonitoringDocumentRepository $monitoringDocumentRepository)
    {
        $this->monitoringDocumentRepository = $monitoringDocumentRepository;
    }

    // public function getMonitoringDocumentByAdvisorIdAndBatch($advisor_id, $batch_id, $filters)
    // {
    //     return $this->monitoringDocumentRepository->getMonitoringDocumentByAdvisorIdAndBatch($advisor_id, $batch_id, $filters);
    // }

    public function getMonitoringByTypeAndMonitoringId($monitoring_id, $type)
    {
        return $this->monitoringDocumentRepository->getMonitoringByTypeAndMonitoringId($monitoring_id, $type);
    }

    // public function addMonitoringDocument($data)
    // {
    //     return $this->monitoringDocumentRepository->createMonitoringDocument($data);
    // }

    public function updateOrCreateMonitoringDocument($data)
    {
        return $this->monitoringDocumentRepository->updateOrCreateMonitoringDocument($data);
    }

    public function getByMonitoringIdAndType($monitoring_id, $type)
    {
        return $this->monitoringDocumentRepository->getByMonitoringIdAndType($monitoring_id, $type);
    }

    public function updateMonitoringDocument($id, $data)
    {
        return $this->monitoringDocumentRepository->updateMonitoringDocument($id, $data);
    }

    public function deleteMonitoringDocument($monitoring_id)
    {
        return $this->monitoringDocumentRepository->deleteMonitoringDocument($monitoring_id);
    }
}
