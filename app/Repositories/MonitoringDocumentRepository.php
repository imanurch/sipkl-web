<?php

namespace App\Repositories;

use App\Models\MonitoringDocument;

class MonitoringDocumentRepository
{
    // public function getByMonitoringId($monitoring_id)
    // {
    //     return MonitoringDocument::where('monitoring_id', $monitoring_id)->get();
    // }

    public function getMonitoringByTypeAndMonitoringId($monitoring_id, $type)
    {
        return MonitoringDocument::where('monitoring_id', $monitoring_id)->where('type', $type)->first();
    }

    public function createMonitoringDocument(array $data)
    {
        return MonitoringDocument::create($data);
    }

    public function getByMonitoringIdAndType($monitoring_id, $type)
    {
        return MonitoringDocument::where('monitoring_id', $monitoring_id)->where('type', $type)->first();
    }

    public function updateMonitoringDocument($id, array $data)
    {
        return MonitoringDocument::where('id', $id)->update($data);
    }

    public function deleteMonitoringDocument($id)
    {
        return MonitoringDocument::where('id', $id)->delete();
    }
}
