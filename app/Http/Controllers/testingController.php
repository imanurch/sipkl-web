<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Advisor;
use App\Models\Logbook;
use App\Models\Student;
use App\Models\Industry;
use App\Models\Assessment;
use App\Models\Monitoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class testingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = 10;
        $dataTable = [
            ["id" => 1, "name" => "Alice", "email" => "alice@example.com", "phone_num" => "081325385045", "status" => "0"],
            ["id" => 2, "name" => "Bob", "email" => "bob@example.com", "phone_num" => "081326547667", "status" => "0"],
            ["id" => 3, "name" => "Charlie", "email" => "charlie@example.com", "phone_num" => "085100654325", "status" => "1"],
        ];
        $statusName = [
            0 => 'Nonaktif',
            1 => 'Aktif',
        ];

        return view('pages.admin.home', [
            'data' => $data,
            'dataTable' => $dataTable,
            'statusName' => $statusName
        ]);
    }

    public function index2()
    {
        $dataTable = [
            (object)[
                'id' => 1,
                'username' => 'Admin1',
                'email' => 'admin1@example.com',
                'phone_num' => '081325385045',
                'created_at' => '2025-01-01 00:00:00',
                'updated_at' => '2025-01-01 00:00:00',
            ],
            (object)[
                'id' => 2,
                'username' => 'Admin2',
                'email' => 'admin2@example.com',
                'phone_num' => '081326547667',
                'created_at' => '2025-01-01 00:00:00',
                'updated_at' => '2025-01-01 00:00:00',
            ],
            (object)[
                'id' => 3,
                'username' => 'Admin3',
                'email' => 'admin3@example.com',
                'phone_num' => '085100654325',
                'created_at' => '2025-01-01 00:00:00',
                'updated_at' => '2025-01-01 00:00:00',
            ]
        ];

        $dataId = (object) ["id" => 1, "username" => "Admin1", "email" => "admin1@example.com", "phone_num" => "081325385045"];

        return view('pages.admin.admin', [
            'dataTable' => $dataTable,
            'dataId' => $dataId
        ]);
    }

    public function dashboard()
    {
        $data = Admin::get();
        return view('pages.admin.dashboard', [
            'data' => $data,
        ]);
    }

    public function admin()
    {
        $data = Admin::get();
        return view('pages.admin.admin', [
            'data' => $data,
        ]);
    }

    public function advisor()
    {
        $data = Advisor::with('department:id,name')->get();
        // $data = Student::get();

        $statusName = [
            0 => 'Nonaktif',
            1 => 'Aktif',
        ];

        return view('pages.admin.advisor', [
            'data' => $data,
            'statusName' => $statusName,
        ]);
    }

    public function student()
    {
        $year = now()->year;
        $data = Student::with('department:id,name')->where('year', '2024')->get();

        // $dataId = Student::find($id);

        $statusName = [
            0 => 'Nonaktif',
            1 => 'Aktif',
        ];

        return view('pages.admin.student', [
            'data' => $data,
            // 'dataId' => $dataId,
            'statusName' => $statusName,
        ]);
    }

    public function industry()
    {
        $data = Industry::where('status', '1')->get();

        $statusName = [
            0 => 'Nonaktif',
            1 => 'Aktif',
        ];
        return view('pages.admin.industry', [
            'data' => $data,
            'statusName' => $statusName,
        ]);
    }

    public function registration()
    {
        $data = Student::get();
        $statusName = [
            0 => 'Nonaktif',
            1 => 'Aktif',
        ];
        return view('pages.admin.registration', [
            'data' => $data,
            'statusName' => $statusName,
        ]);
    }

    public function intern()
    {
        $data = Student::get();
        return view('pages.admin.intern', [
            'data' => $data,
        ]);
    }

    public function output()
    {
        $data = Student::get();
        $statusName = [
            0 => 'Nonaktif',
            1 => 'Aktif',
        ];
        return view('pages.admin.output', [
            'data' => $data,
            'statusName' => $statusName,
        ]);
    }

    public function assessment()
    {
        $data = Student::get();
        $statusName = [
            0 => 'Tidak Lulus',
            1 => 'Lulus',
        ];
        return view('pages.admin.assessment', [
            'data' => $data,
            'statusName' => $statusName,
        ]);
    }

    public function document()
    {
        // $data = Advisor::get();
        return view('pages.admin.document', [
            // 'data' => $data,
        ]);
    }



    // public function studentId(){
    //     $dataId = Student::find($id);

    //     $statusName = [
    //         0 => 'Nonaktif',
    //         1 => 'Aktif',
    //     ];

    //     return view('pages.admin.student', [
    //         'dataTable' => $data,
    //         'dataId' => $dataId,
    //         'statusName' => $statusName,
    //     ]);
    // }

    // public function test()
    // {
    //     // URL API
    //     $url = 'http://127.0.0.1:8000/api/adminManagement';
    //     $urlID = 'http://127.0.0.1:8000/api/adminManagement/1';

    //     // Mengambil data dari API
    //     $response = Http::get($url);

    //     // $responseID = Http::get($urlID);
    //     $dataId = (object) ["id" => 1, "username" => "Admin1", "email" => "admin1@example.com", "phone_num" => "081325385045"];

    //     if ($response->successful()) {
    //         // Mengubah response menjadi array
    //         $dataTable = $response;
    //         // $dataId = $responseID->json()['data'];
    //         return view('pages.admin.admin', [
    //             'dataTable' => $dataTable,
    //             'dataId' => $dataId
    //         ]);
    //     } else {
    //         // Tangani error jika request gagal
    //         return response()->json(['error' => 'Failed to fetch data from API'], 500);
    //     }
    // }

    // advisor
    public function dashboardAdv()
    {
        $data = Advisor::find(1);
        return view('pages.advisor.dashboard', [
            'data' => $data,
        ]);
    }

    public function internAdv()
    {
        $data = Student::get();
        return view('pages.advisor.intern', [
            'data' => $data,
        ]);
    }

    public function industryAdv()
    {
        $data = Industry::get();
        return view('pages.advisor.industry', [
            'data' => $data,
        ]);
    }

    public function monitoringAdv()
    {
        $data = Student::get();
        return view('pages.advisor.monitoring', [
            'data' => $data,
        ]);
    }

    public function logbookAdv()
    {
        $data = Student::get();
        return view('pages.advisor.logbook', [
            'data' => $data,
        ]);
    }

    public function logbookIdAdv()
    {
        $data = Student::find(1);
        return view('pages.advisor.logbook_detail', [
            'data' => $data,
        ]);
    }

    public function assessmentAdv()
    {
        $data = Student::get();
        $statusName = [
            0 => 'Tidak Lulus',
            1 => 'Lulus',
        ];
        return view('pages.advisor.assessment', [
            'data' => $data,
            'statusName' => $statusName,
        ]);
    }

    // student
    public function dashboardStud()
    {
        $data = Student::find(1);
        return view('pages.student.dashboard', [
            'data' => $data,
        ]);
    }
    public function registrasiStud()
    {
        $data = Student::find(1);
        $status = 1;
        $statusName = [
            0 => 'Sedang Diverifikasi',
            1 => 'Pendaftaran PKL Diterima',
            2 => 'Pendaftaran PKL Ditolak',
        ];

        return view('pages.student.registration', [
            'data' => $data,
            'status' => $status,
            'statusName' => $statusName,
        ]);
    }
    public function logbookStud()
    {
        $data = Student::find(1);
        $statusName = [
            0 => 'Belum Diisi',
            1 => 'Disetujui',
            2 => 'Perlu Revisi',
        ];
        return view('pages.student.logbook', [
            'data' => $data,
            'statusName' => $statusName,
        ]);
    }
    public function reportStud()
    {
        $data = Student::find(1);
        return view('pages.student.final_report', [
            'data' => $data,
        ]);
    }
}
