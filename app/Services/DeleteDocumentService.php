<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class DeleteDocumentService
{
    public function deleteRegistrationDocument($type, $filename)
    {
        $type = str_replace(' ', '_', $type);
        Storage::delete('registration_document/' . $type . '/' . $filename);
    }

    public function deleteMonitoringDocument($type, $filename)
    {
        $type = str_replace(' ', '_', $type);
        Storage::delete('monitoring_documents/' . $type . '/' . $filename);
    }

    public function deleteInternDocument($filename)
    {
        Storage::delete('monitoring_documents/laporan_akhir/'. $filename);
    }
}
