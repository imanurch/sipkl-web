<?php

namespace App\Repositories;

use App\Models\MonitoringDocument;

class MonitoringDocumentRepository
{
    /**
     * Get a monitoring document by monitoring ID and type.
     *
     * @param int $monitoring_id
     * @param string $type
     * @return \App\Models\MonitoringDocument|null
     */
    public function getMonitoringByTypeAndMonitoringId($monitoring_id, $type)
    {
        return MonitoringDocument::where('monitoring_id', $monitoring_id)->where('type', $type)->first();
    }

    /**
     * Update or create a monitoring document.
     *
     * @param array $data
     * @return \App\Models\MonitoringDocument
     */
    public function updateOrCreateMonitoringDocument(array $data)
    {
        return MonitoringDocument::updateOrCreate(
            [
                'monitoring_id' => $data['monitoring_id'],
                'type' => $data['type'],
            ],
            ['url' => $data['url']]
        );
    }

    /**
     * Get a monitoring document by monitoring ID and type.
     *
     * @param int $monitoring_id
     * @param string $type
     * @return \App\Models\MonitoringDocument|null
     */
    public function getByMonitoringIdAndType($monitoring_id, $type)
    {
        return MonitoringDocument::where('monitoring_id', $monitoring_id)->where('type', $type)->first();
    }

    /**
     * Get all monitoring documents by monitoring ID.
     *
     * @param int $monitoring_id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMonitoringDocumentByMonitoringId($monitoring_id)
    {
        return MonitoringDocument::where('monitoring_id', $monitoring_id)->get();
    }

    /**
     * Update a monitoring document by its ID.
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function updateMonitoringDocument($id, array $data)
    {
        return MonitoringDocument::where('id', $id)->update($data);
    }

    /**
     * Delete monitoring documents by monitoring ID.
     *
     * @param int $monitoring_id
     * @return int
     */
    public function deleteMonitoringDocument($monitoring_id)
    {
        return MonitoringDocument::where('monitoring_id', $monitoring_id)->delete();
    }
}
