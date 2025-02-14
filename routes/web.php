<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\testingController;
use App\Http\Controllers\Admin\OutputController;
use App\Http\Controllers\Admin\LogbookAdminController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\AssessmentAdminController;
use App\Http\Controllers\Admin\BatchManagementController;
use App\Http\Controllers\Admin\InternshipAdminController;
use App\Http\Controllers\Advisor\InternAdvisorController;
use App\Http\Controllers\Admin\AdvisorManagementController;
use App\Http\Controllers\Admin\RegistrationAdminController;
use App\Http\Controllers\Admin\StudentManagementController;
use App\Http\Controllers\Advisor\IndustryAdvisorController;
use App\Http\Controllers\Admin\IndustryManagementController;
use App\Http\Controllers\Advisor\DashboardAdvisorController;
use App\Http\Controllers\Student\DashboardStudentController;
use App\Http\Controllers\Student\RegistrationStudentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/content', function () {
    return view('components.content');
});

// Route::get('/sipkl-admin', function () {
//     return view('pages.admin.home');
// });

Route::get('/test', [testingController::class, 'index']);
Route::get('/sipkl-beranda', [testingController::class, 'dashboard']);
Route::get('/sipkl-manajemen-admin', [testingController::class, 'admin']);
Route::get('/sipkl-manajemen-siswa', [testingController::class, 'student']);
Route::get('/sipkl-manajemen-guru', [testingController::class, 'advisor']);
Route::get('/sipkl-manajemen-industri', [testingController::class, 'industry']);
Route::get('/sipkl-registrasi', [testingController::class, 'registration']);
Route::get('/sipkl-intern', [testingController::class, 'intern']);
Route::get('/sipkl-output', [testingController::class, 'output']);
Route::get('/sipkl-assessment', [testingController::class, 'assessment']);
Route::get('/sipkl-document', [testingController::class, 'document']);
// Route::get('/sipkl-manajemen-siswa/{id}', [testingController::class, 'student']);
// Route::get('/testAPI', [testingController::class, 'test']);

Route::get('/sipkladv-beranda', [testingController::class, 'dashboardAdv']);
Route::get('/sipkladv-intern', [testingController::class, 'internAdv']);
Route::get('/sipkladv-industry', [testingController::class, 'industryAdv']);
Route::get('/sipkladv-monitoring', [testingController::class, 'monitoringAdv']);
Route::get('/sipkladv-logbook', [testingController::class, 'logbookAdv']);
Route::get('/sipkladv-logbook-id', [testingController::class, 'logbookIdAdv']);
Route::get('/sipkladv-assessment', [testingController::class, 'assessmentAdv']);

Route::get('/sipklstud-beranda', [testingController::class, 'dashboardStud']);
Route::get('/sipklstud-registrasi', [testingController::class, 'registrasiStud']);
Route::get('/sipklstud-logbook', [testingController::class, 'logbookStud']);
Route::get('/sipklstud-report', [testingController::class, 'reportStud']);


// draft
Route::get('/admin', [AdminManagementController::class, 'index']);
Route::post('/admin-store', [AdminManagementController::class, 'store']);
Route::patch('/admin-update/{id}', [AdminManagementController::class, 'update']);
Route::delete('/admin-delete/{id}', [AdminManagementController::class, 'destroy']);

Route::get('/advisor', [AdvisorManagementController::class, 'index']);

// save for later
Route::prefix('admin')->name('admin.')->group(function () {
    // dashboard
    Route::get('dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');

    // admin management
    Route::get('adminManagement', [AdminManagementController::class, 'index'])->name('adminManagement');
    Route::post('/adminManagement', [AdminManagementController::class, 'store'])->name('adminManagement.store');
    Route::patch('/adminManagement/{id}', [AdminManagementController::class, 'update'])->name('adminManagement.update');
    Route::delete('/adminManagement/{id}', [AdminManagementController::class, 'destroy'])->name('adminManagement.destroy');

    // advisor management
    Route::get('advisorManagement', [AdvisorManagementController::class, 'index'])->name('advisorManagement');
    Route::post('/advisorManagement', [AdvisorManagementController::class, 'store'])->name('advisorManagement.store');
    Route::patch('/advisorManagement/{id}', [AdvisorManagementController::class, 'update'])->name('advisorManagement.update');
    Route::delete('/advisorManagement/{id}', [AdvisorManagementController::class, 'destroy'])->name('advisorManagement.destroy');

    // student management
    Route::get('studentManagement', [StudentManagementController::class, 'index'])->name('studentManagement');
    Route::post('/studentManagement', [StudentManagementController::class, 'store'])->name('studentManagement.store');
    Route::patch('/studentManagement/{id}', [StudentManagementController::class, 'update'])->name('studentManagement.update');
    Route::delete('/studentManagement/{id}', [StudentManagementController::class, 'destroy'])->name('studentManagement.destroy');

    // industry management
    Route::get('industryManagement', [IndustryManagementController::class, 'index'])->name('industryManagement');
    Route::post('/industryManagement', [IndustryManagementController::class, 'store'])->name('industryManagement.store');
    Route::patch('/industryManagement/{id}', [IndustryManagementController::class, 'update'])->name('industryManagement.update');
    Route::delete('/industryManagement/{id}', [IndustryManagementController::class, 'destroy'])->name('industryManagement.destroy');

    // batch management
    Route::get('batchManagement', [BatchManagementController::class, 'index'])->name('batchManagement');
    Route::post('/batchManagement', [BatchManagementController::class, 'store'])->name('batchManagement.store');
    Route::patch('/batchManagement/{id}', [BatchManagementController::class, 'update'])->name('batchManagement.update');
    Route::delete('/batchManagement/{id}', [BatchManagementController::class, 'destroy'])->name('batchManagement.destroy');

    // registration
    Route::get('registration', [RegistrationAdminController::class, 'index'])->name('registration');
    Route::get('registration/download/{filename}', [RegistrationAdminController::class, 'downloadFile'])->name('registration.download.file');
    Route::get('registration/confirmation/{registrationId}/{status}', [RegistrationAdminController::class, 'confirmStatusRegistration'])->name('registration.status.confirm');
    // Route::post('/registration', [registrationAdminController::class, 'store'])->name('registrationAdmin.store');
    // Route::patch('/registration/{id}', [registrationAdminController::class, 'update'])->name('registrationAdmin.update');
    // Route::delete('/registration/{id}', [registrationAdminController::class, 'destroy'])->name('registrationAdmin.destroy');

    // intern
    Route::get('intern', [InternshipAdminController::class, 'index'])->name('intern');
    // Route::post('/intern', [InternshipAdminController::class, 'store'])->name('intern.store');
    // Route::patch('/intern/{id}', [InternshipAdminController::class, 'update'])->name('intern.update');
    // Route::delete('/intern/{id}', [InternshipAdminController::class, 'destroy'])->name('intern.destroy');

    // output
    Route::get('output', [OutputController::class, 'index'])->name('output');
    // Route::post('/intern', [internAdminController::class, 'store'])->name('internAdmin.store');
    // Route::patch('/intern/{id}', [internAdminController::class, 'update'])->name('internAdmin.update');
    // Route::delete('/intern/{id}', [internAdminController::class, 'destroy'])->name('internAdmin.destroy');
    Route::get('output/logbook/{batch_id}/{id}', [LogbookAdminController::class, 'index'])->name('outputAdmin.logbook');

    // assessment
    Route::get('assessment', [AssessmentAdminController::class, 'index'])->name('assessment');
    Route::post('/intern', [AssessmentAdminController::class, 'store'])->name('assessment.store');
    Route::patch('/intern/{id}', [AssessmentAdminController::class, 'update'])->name('assessment.update');
    Route::delete('/intern/{id}', [AssessmentAdminController::class, 'destroy'])->name('assessment.destroy');

    // document
    Route::get('document', [OutputController::class, 'index'])->name('document');
    // Route::post('/intern', [internAdminController::class, 'store'])->name('internAdmin.store');
    // Route::patch('/intern/{id}', [internAdminController::class, 'update'])->name('internAdmin.update');
    // Route::delete('/intern/{id}', [internAdminController::class, 'destroy'])->name('internAdmin.destroy');

});

Route::prefix('advisor')->name('advisor.')->group(function () {
    // dashboard
    Route::get('dashboard', [DashboardAdvisorController::class, 'index'])->name('dashboard');

    // intern
    Route::get('intern', [InternAdvisorController::class, 'index'])->name('intern');

    // industry
    Route::get('industry', [IndustryAdvisorController::class, 'index'])->name('industry');
});

Route::prefix('student')->name('student.')->group(function () {
    // dashboard
    Route::get('dashboard', [DashboardStudentController::class, 'index'])->name('dashboard');

    // registration
    Route::get('registration', [RegistrationStudentController::class, 'index'])->name('registration');
    Route::post('registration', [RegistrationStudentController::class, 'newIndustryRequest'])->name('registration.industry.request');
    Route::post('registration/step2', [RegistrationStudentController::class, 'step2'])->name('registration.step2');
    Route::post('registration/step3', [RegistrationStudentController::class, 'step3'])->name('registration.step3');
    Route::post('registration/step4', [RegistrationStudentController::class, 'step4'])->name('registration.step4');
    Route::post('registration/step5', [RegistrationStudentController::class, 'step5'])->name('registration.step5');
    Route::get('registration/download/{filename}', [RegistrationStudentController::class, 'downloadFile'])->name('registration.download.file');
});
