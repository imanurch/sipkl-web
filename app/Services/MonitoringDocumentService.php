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

    public function addMonitoringDocument($data)
    {
        return $this->monitoringDocumentRepository->createMonitoringDocument($data);
    }

    // public function updateMonitoringDocument($id, $data)
    // {
    //     return $this->monitoringDocumentRepository->updateMonitoringDocument($id, $data);
    // }

    // public function deleteMonitoringDocument($id)
    // {
    //     return $this->monitoringDocumentRepository->deleteMonitoringDocument($id);
    // }
}
