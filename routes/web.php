<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\OutputController;
use App\Http\Controllers\GenerateTestingController;
use App\Http\Controllers\Admin\AccountAdminController;
use App\Http\Controllers\Admin\LogbookAdminController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\AssessmentAdminController;
use App\Http\Controllers\Admin\BatchManagementController;
use App\Http\Controllers\Admin\InternshipAdminController;
use App\Http\Controllers\Admin\MonitoringAdminController;
use App\Http\Controllers\Advisor\InternAdvisorController;
use App\Http\Controllers\Advisor\AccountAdvisorController;
use App\Http\Controllers\Advisor\LogbookAdvisorController;
use App\Http\Controllers\Student\LogbookStudentController;
use App\Http\Controllers\Admin\AdvisorManagementController;
use App\Http\Controllers\Admin\RegistrationAdminController;
use App\Http\Controllers\Admin\StudentManagementController;
use App\Http\Controllers\Advisor\IndustryAdvisorController;
use App\Http\Controllers\Admin\AdministrationDataController;
use App\Http\Controllers\Admin\IndustryManagementController;
use App\Http\Controllers\Advisor\DashboardAdvisorController;
use App\Http\Controllers\Student\DashboardStudentController;
use App\Http\Controllers\Advisor\AssessmentAdvisorController;
use App\Http\Controllers\Advisor\MonitoringAdvisorController;
use App\Http\Controllers\Student\FinalReportStudentController;
use App\Http\Controllers\Student\RegistrationStudentController;
use App\Http\Controllers\Advisor\LogbookDetailAdvisorController;
use App\Http\Controllers\Student\AccountStudentController;

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

// Route::get('', function () {
//     return view('welcome');
// });

// Route::get('content', function () {
//     return view('components.content');
// });

// Route::get('sipkl-admin', function () {
//     return view('pages.admin.home');
// });


// Route::get('testing', [GenerateTestingController::class, 'index'])->name('testing');
// Route::get('testing/generate', [GenerateTestingController::class, 'create'])->name('testing.generate');


// Route::get('doc', function () {
//     // return view('document_templates/surat_pengantar_template');
//     return view('document_templates/surat_tugas');
// });

Route::get('', [AuthenticationController::class, 'index'])->name('sipkl');
Route::post('login', [AuthenticationController::class, 'login'])->name('sipkl.login');
Route::get('logout', [AuthenticationController::class, 'logout'])->name('sipkl.logout');
Route::get('account', [AccountController::class, 'index'])->name('sipkl.account');

Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
    // dashboard
    Route::get('dashboard', [DashboardAdminController::class, 'index'])->name('dashboard');

    // admin management
    Route::get('adminManagement', [AdminManagementController::class, 'index'])->name('adminManagement');
    Route::post('adminManagement', [AdminManagementController::class, 'store'])->name('adminManagement.store');
    Route::patch('adminManagement/{id}', [AdminManagementController::class, 'update'])->name('adminManagement.update');
    Route::delete('adminManagement/{id}', [AdminManagementController::class, 'destroy'])->name('adminManagement.destroy');

    // advisor management
    Route::get('advisorManagement', [AdvisorManagementController::class, 'index'])->name('advisorManagement');
    Route::get('advisorManagement/downloadTemplate', [AdvisorManagementController::class, 'downloadTemplateFile'])->name('advisorManagement.downloadTemplateFile');
    Route::post('advisorManagement/import', [AdvisorManagementController::class, 'import'])->name('advisorManagement.import');
    Route::post('advisorManagement/export', [AdvisorManagementController::class, 'export'])->name('advisorManagement.export');
    Route::post('advisorManagement', [AdvisorManagementController::class, 'store'])->name('advisorManagement.store');
    Route::patch('advisorManagement/{id}', [AdvisorManagementController::class, 'update'])->name('advisorManagement.update');
    Route::delete('advisorManagement/{id}', [AdvisorManagementController::class, 'destroy'])->name('advisorManagement.destroy');

    // student management
    Route::get('studentManagement', [StudentManagementController::class, 'index'])->name('studentManagement');
    Route::get('studentManagement/downloadTemplate', [StudentManagementController::class, 'downloadTemplateFile'])->name('studentManagement.downloadTemplateFile');
    Route::post('studentManagement/import', [StudentManagementController::class, 'import'])->name('studentManagement.import');
    Route::post('studentManagement', [StudentManagementController::class, 'store'])->name('studentManagement.store');
    Route::patch('studentManagement/{id}', [StudentManagementController::class, 'update'])->name('studentManagement.update');
    Route::delete('studentManagement/{id}', [StudentManagementController::class, 'destroy'])->name('studentManagement.destroy');

    // industry management
    Route::get('industryManagement', [IndustryManagementController::class, 'index'])->name('industryManagement');
    Route::get('industryManagement/downloadTemplate', [IndustryManagementController::class, 'downloadTemplateFile'])->name('industryManagement.downloadTemplateFile');
    Route::post('industryManagement/import', [IndustryManagementController::class, 'import'])->name('industryManagement.import');
    Route::post('industryManagement/export', [IndustryManagementController::class, 'export'])->name('industryManagement.export');
    Route::post('industryManagement', [IndustryManagementController::class, 'store'])->name('industryManagement.store');
    Route::patch('industryManagement/{id}', [IndustryManagementController::class, 'update'])->name('industryManagement.update');
    Route::delete('industryManagement/{id}', [IndustryManagementController::class, 'destroy'])->name('industryManagement.destroy');
    Route::get('industryRequest/confirmation/{industryId}/{status}', [IndustryManagementController::class, 'confirmStatusIndustry'])->name('industryRequest.status.confirm');
    Route::patch('industryManagement/updateStatusRejectedIndustry/{id}', [IndustryManagementController::class, 'updateStatusIndustry'])->name('industryManagement.updateStatusRejectedIndustry');

    // batch management
    Route::get('batchManagement', [BatchManagementController::class, 'index'])->name('batchManagement');
    Route::post('batchManagement', [BatchManagementController::class, 'store'])->name('batchManagement.store');
    Route::patch('batchManagement/{id}', [BatchManagementController::class, 'update'])->name('batchManagement.update');
    Route::get('batchManagement/setActiveBatch/{id}', [BatchManagementController::class, 'setActiveBatch'])->name('batchManagement.updateActiveBatch');
    Route::delete('batchManagement/{id}', [BatchManagementController::class, 'destroy'])->name('batchManagement.destroy');

    // registration
    Route::get('registration', [RegistrationAdminController::class, 'index'])->name('registration');
    Route::delete('registration/{id}', [RegistrationAdminController::class, 'destroy'])->name('registration.destroy');
    Route::get('registration/download/{type}/{filename}', [RegistrationAdminController::class, 'downloadFile'])->name('registration.download.file');
    Route::get('registration/confirmation/{registrationId}/{status}', [RegistrationAdminController::class, 'confirmStatusRegistration'])->name('registration.status.confirm');
    Route::post('registration/confirmation/{registrationId}', [RegistrationAdminController::class, 'updateStatusRegistration'])->name('registration.update.status');
    // Route::get('registration/generateDokumenPermohonan/{registrationId}', [RegistrationAdminController::class, 'generateSuratPermohonan'])->name('registration.generateSuratPermohonan');
    Route::post('registration/generateDokumen', [RegistrationAdminController::class, 'generateDocument'])->name('registration.generateDocument');

    // intern
    Route::get('intern', [InternshipAdminController::class, 'index'])->name('intern');
    Route::patch('intern/updateAdvisor/{id}', [InternshipAdminController::class, 'updateAdvisor'])->name('intern.updateAdvisor');
    Route::delete('intern/delete/{id}', [InternshipAdminController::class, 'destroy'])->name('intern.destroy');
    Route::post('intern/generateDokumen', [InternshipAdminController::class, 'generateDocument'])->name('intern.generateDocument');
    Route::get('intern/downloadFile/{filename}', [InternshipAdminController::class, 'downloadFile'])->name('intern.downloadFile');

    // output
    Route::get('output', [OutputController::class, 'index'])->name('output');
    Route::get('output/logbook/{batch_id}/{id}', [LogbookAdminController::class, 'index'])->name('outputAdmin.logbook');
    Route::get('output/download/{filename}', [OutputController::class, 'downloadFinalReport'])->name('output.download.finalReport');

    Route::get('logbook/detail/{studentId}/{internshipId}', [LogbookDetailAdvisorController::class, 'index'])->name('logbook.detail');
    Route::patch('logbook/detail/confirm/{logbookId}/{status}', [LogbookDetailAdvisorController::class, 'statusConfirm'])->name('logbook.detail.confirm');

    // monitoring
    Route::get('monitoring', [MonitoringAdminController::class, 'index'])->name('monitoring');
    Route::post('monitoring', [MonitoringAdminController::class, 'store'])->name('monitoring.store');
    Route::patch('monitoring/{id}', [MonitoringAdminController::class, 'update'])->name('monitoring.update');
    Route::delete('monitoring/{id}', [MonitoringAdminController::class, 'destroy'])->name('monitoring.destroy');
    Route::post('monitoring/generateSurat', [MonitoringAdminController::class, 'generateSurat'])->name('monitoring.generateSurat');
    Route::get('monitoring/download/{type}/{filename}', [MonitoringAdminController::class, 'downloadFile'])->name('monitoring.downloadFile');

    // assessment
    Route::get('assessment', [AssessmentAdminController::class, 'index'])->name('assessment');
    Route::patch('assessment/{id}', [AssessmentAdminController::class, 'update'])->name('assessment.update');
    Route::post('assessment/export', [AssessmentAdminController::class, 'export'])->name('assessment.export');

    // administration data
    Route::get('administrationData', [AdministrationDataController::class, 'index'])->name('administrationData');
    // Route::post('administrationData/advisorDocumentSearch', [DocumentController::class, 'advisorDocumentSearch'])->name('document.advisorSearch');
    Route::post('administrationData', [AdministrationDataController::class, 'update'])->name('administrationData.update');
    Route::get('administrationData/{filename}', [AdministrationDataController::class, 'downloadFile'])->name('administrationData.downloadFile');

    // account
    Route::get('account', [AccountAdminController::class, 'index'])->name('account');
    Route::post('account/updateAccount', [AccountAdminController::class, 'updateAccount'])->name('account.updateAccount');
    Route::post('account/updateProfile', [AccountAdminController::class, 'updateProfile'])->name('account.updateProfile');
});

Route::prefix('advisor')->name('advisor.')->middleware('role:advisor')->group(function () {
    // dashboard
    Route::get('dashboard', [DashboardAdvisorController::class, 'index'])->name('dashboard');
    Route::get('dashboard/download/{filename}', [DashboardAdvisorController::class, 'downloadSuratTugas'])->name('dashboard.downloadSuratTugas');

    // intern
    Route::get('intern', [InternAdvisorController::class, 'index'])->name('intern');

    // industry
    Route::get('industry', [IndustryAdvisorController::class, 'index'])->name('industry');

    // monitoring
    Route::get('monitoring', [MonitoringAdvisorController::class, 'index'])->name('monitoring');
    Route::get('monitoring/download/{type}/{filename}', [MonitoringAdvisorController::class, 'downloadFile'])->name('monitoring.downloadFile');
    Route::post('monitoring', [MonitoringAdvisorController::class, 'store'])->name('monitoring.store');
    Route::patch('monitoring/{id}', [MonitoringAdvisorController::class, 'update'])->name('monitoring.update');
    Route::delete('monitoring/{id}', [MonitoringAdvisorController::class, 'destroy'])->name('monitoring.destroy');

    // logbook
    Route::get('logbook', [LogbookAdvisorController::class, 'index'])->name('logbook');
    // logbook - detail
    Route::get('logbook/detail/{studentId}/{internshipId}', [LogbookDetailAdvisorController::class, 'index'])->name('logbook.detail');
    Route::patch('logbook/detail/confirm/{logbookId}/{status}', [LogbookDetailAdvisorController::class, 'statusConfirm'])->name('logbook.detail.confirm');

    // assessment
    Route::get('assessment', [AssessmentAdvisorController::class, 'index'])->name('assessment');
    Route::patch('assessment/{id}', [AssessmentAdvisorController::class, 'update'])->name('assessment.update');
    Route::get('assessment/download/{filename}', [AssessmentAdvisorController::class, 'downloadLaporanAkhir'])->name('assessment.download.finalReport');

    // account
    Route::get('account', [AccountAdvisorController::class, 'index'])->name('account');
    Route::post('account/updateAccount', [AccountAdvisorController::class, 'updateAccount'])->name('account.updateAccount');
    Route::post('account/updateProfile', [AccountAdvisorController::class, 'updateProfile'])->name('account.updateProfile');
});

Route::prefix('student')->name('student.')->middleware('role:student')->group(function () {
    // dashboard
    Route::get('dashboard', [DashboardStudentController::class, 'index'])->name('dashboard');

    // registration
    Route::get('registration', [RegistrationStudentController::class, 'index'])->name('registration');
    Route::post('registration/industryRequest', [RegistrationStudentController::class, 'newIndustryRequest'])->name('registration.industry.request');
    Route::post('registration/step2', [RegistrationStudentController::class, 'step2'])->name('registration.step2');
    Route::post('registration/step3', [RegistrationStudentController::class, 'step3'])->name('registration.step3');
    Route::post('registration/step4', [RegistrationStudentController::class, 'step4'])->name('registration.step4');
    Route::get('registration/step4', [RegistrationStudentController::class, 'step4View'])->name('registration.step4');
    Route::post('registration/step5', [RegistrationStudentController::class, 'step5'])->name('registration.step5');
    Route::get('registration/step5', [RegistrationStudentController::class, 'step5View'])->name('registration.step5');
    Route::get('registration/download/{type}/{filename}', [RegistrationStudentController::class, 'downloadFile'])->name('registration.download.file');
    Route::get('registration/history', [RegistrationStudentController::class, 'history'])->name('registration.history');
    Route::get('registration/{lastRegistrationId}', [RegistrationStudentController::class, 'repeatRegistration'])->name('registration.newRegistration');

    // logbook
    Route::get('logbook', [LogbookStudentController::class, 'index'])->name('logbook');
    Route::patch('logbook/{id}', [LogbookStudentController::class, 'update'])->name('logbook.update');

    // final-report
    Route::get('final-report', [FinalReportStudentController::class, 'index'])->name('finalReport');
    Route::post('final-report', [FinalReportStudentController::class, 'store'])->name('finalReport.store');
    Route::get('final-report/download/{filename}', [FinalReportStudentController::class, 'downloadLaporanAkhir'])->name('finalReport.downloadLaporanAkhir');
    
    // account
    Route::get('account', [AccountStudentController::class, 'index'])->name('account');
    Route::post('account/updateAccount', [AccountStudentController::class, 'updateAccount'])->name('account.updateAccount');
    Route::post('account/updateProfile', [AccountStudentController::class, 'updateProfile'])->name('account.updateProfile');
});
