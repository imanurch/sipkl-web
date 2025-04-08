<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\User;
use App\Models\Admin;
use App\Models\Advisor;
use App\Models\Student;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class GenerateTestingController extends Controller
{
    public function index()
    {
        return view('testing', [
            'pages' => 'testing',
        ]);
    }

    public function create()
    {
        $data = [
            'create_date'=>date('d M Y'),
            'purpose_name' => 'PT Indomarco',
            'purpose_address'  => 'tempat',
        ];

        $pdf = Pdf::loadView('document_templates/surat_permohonan_pkl', $data);
        // $filename = 'surat_pengantar_' . time() . '.pdf';
        // $path = storage_path('app/registration_document/surat_pengantar/' . $filename);
        // $pdf->save($path);

        // Cara untuk download
        // return $pdf->download('dokumen.pdf');

        // Atau, untuk menampilkan PDF di browser:
        return $pdf->stream('dokumen.pdf');
    }
}
