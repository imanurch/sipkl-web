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

    public function getMonitoringByTypeAndMonitoringId($monitoring_id, $type)
    {
        return $this->monitoringDocumentRepository->getMonitoringByTypeAndMonitoringId($monitoring_id, $type);
    }

    public function updateOrCreateMonitoringDocument($data)
    {
        return $this->monitoringDocumentRepository->updateOrCreateMonitoringDocument($data);
    }

    public function getByMonitoringIdAndType($monitoring_id, $type)
    {
        return $this->monitoringDocumentRepository->getByMonitoringIdAndType($monitoring_id, $type);
    }

    public function getMonitoringDocumentByMonitoringId($monitoring_id)
    {
        return $this->monitoringDocumentRepository->getMonitoringDocumentByMonitoringId($monitoring_id);
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
