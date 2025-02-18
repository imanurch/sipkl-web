<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Services\InternshipService;
use App\Http\Controllers\Controller;
use App\Services\AdvisorService;
use App\Services\BatchService;

class AuthController extends Controller
{
    // protected $internshipService, $advisorService, $batchService;

    // Constructor Injection
    // public function __construct(InternshipService $internshipService, AdvisorService $advisorService, BatchService $batchService)
    // {
    //     $this->internshipService = $internshipService;
    //     $this->advisorService = $advisorService;
    //     $this->batchService = $batchService;
    // }

    public function index(Request $request)
    {        
        return view('auth.login');
    }
}
