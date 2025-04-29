<?php

namespace App\Services;

use Flasher\Toastr\Laravel\Facade\Toastr;

class DownloadService
{
    public function templateImportDataDownload($filename)
    {
        $filePath = storage_path('app/public/files/' . $filename);

        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            Toastr::addError('File tidak ditemukan');
            return null;
        }
    }

    public function internDocumentDownload($type, $filename)
    {
        $type = str_replace(' ', '_', $type);
        $path = storage_path('app/intern_documents/' . $type . '/' . $filename);

        if (file_exists($path)) {
            // return response()->download($path);
            return response()->file($path);
        } else {
            return response()->json(['message' => 'File tidak ditemukan'], 404);
        }
    }

    public function monitoringDocumentDownload($type, $filename)
    {
        $type = str_replace(' ', '_', $type);
        $path = storage_path('app/monitoring_documents/' . $type . '/' . $filename);

        if (file_exists($path)) {
            // return response()->download($path);
            return response()->file($path);
        } else {
            return response()->json(['message' => 'File tidak ditemukan'], 404);
            // Toastr::addError('File tidak ditemukan!');
        }
    }

    public function registrationDocumentDownload($type, $filename)
    {
        $type = str_replace(' ', '_', $type);
        $path = storage_path('app/registration_documents/' . $type . '/' . $filename);

        if (file_exists($path)) {
            // return response()->download($path);
            return response()->file($path);
        } else {
            return response()->json(['message' => 'File tidak ditemukan'], 404);
            // Toastr::addError('File tidak ditemukan!');
        }
    }
}
