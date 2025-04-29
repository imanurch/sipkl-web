<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;

class DocumentGenerateService
{
    public function registrationDocumentGenerate($data)
    {
        $pdf = Pdf::loadView('document_templates/surat_permohonan_pkl', $data);
        $filename = 'surat_permohonan_' . time() . '.pdf';
        $path = storage_path('app/registration_documents/surat_permohonan/' . $filename);

        $pdf->save($path);

        return $filename;
    }

    public function monitoringDocumentGenerate($type, $data)
    {
        $name = $type == 'surat tugas' ? 'surat_tugas_advisor' : ($type == 'sppd' ? 'sppd_advisor' : ($type == 'surat pengantar' ? 'surat_pelepasan_intern' : 'surat_penarikan_intern'));
        $type = str_replace(' ', '_', $type);

        $pdf = Pdf::loadView('document_templates/' . $name, $data);
        $filename = $type . '_' . time() . '.pdf';

        $path = storage_path('app/monitoring_documents/' . $type . '/' . $filename);
        $pdf->save($path);

        return $filename;
    }

    public function internDocumentGenerate($data, $name)
    {
        $pdf = Pdf::loadView('document_templates/surat_jalan', $data);
        $filename = 'surat_jalan_' . $name . '.pdf';
        $path = storage_path('app/intern_documents/surat_jalan/' . $filename);

        $pdf->save($path);

        return $filename;
    }
}
