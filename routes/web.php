<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\StudentBalanceController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StudentParentController;
use App\Http\Controllers\ClassAdvisorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::middleware(['auth'])->group(function () {
    /* --- */
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/announcement', [HomeController::class, 'announcement'])->name('announcement.edit');
    Route::post('/announcement', [HomeController::class, 'updateAnnouncement'])->name('announcement.update');

    /* --- */
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');

    /* --- */
    Route::get('classrooms/{id}/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
    Route::post('/students/class/change', [StudentController::class, 'changeClass'])->name('students.change-class');
    Route::post(
        '/students/import-excel',
        [StudentController::class, 'importExcel']
    )->name('students.import-excel');
    Route::get(
        '/students/excel/export',
        [StudentController::class, 'exportExcel']
    )->name('students.export-excel');
    Route::post('/students/setting', [StudentController::class, 'updateBalanceSetting'])->name('students.update-setting');
    Route::get('student/qr-code/{id}', [StudentController::class, 'qrCode'])->name('students.qr-code');

    Route::get('classrooms/qr-code', [StudentController::class, 'qrCodeStudentByClassroom'])->name('students.qr-code.classrooms');
    Route::get('student/qr-code/{id}/students', [StudentController::class, 'qrCodeAll'])->name('students.qr-code.all');
    Route::get('student/qr-code/{id}/students/print', [StudentController::class, 'printQrCodeAll'])->name('students.qr-code.all.print');

    Route::get('/students/all/json', [StudentController::class, 'getStudentsJson']);
    Route::get('/students/{id}/json', [StudentController::class, 'getEdit']);
    Route::get('/classrooms/{id}/students/json', [StudentController::class, 'getStudentsByClassroom']);
    Route::get('/students/{id}/setting', [StudentController::class, 'getBalanceSetting']);

    /* --- */
    Route::resource('/teachers', TeacherController::class);
    Route::get('/teachers/{id}/json', [TeacherController::class, 'getEdit']);
    Route::resource('/class-advisors', ClassAdvisorController::class);
    Route::get('/class-advisors/{id}/json', [ClassAdvisorController::class, 'getEdit']);
    Route::post(
        '/teachers/import-excel',
        [TeacherController::class, 'importExcel']
    )->name('teachers.import-excel');
    Route::get(
        '/teachers/excel/export',
        [TeacherController::class, 'exportExcel']
    )->name('teachers.export-excel');

    /* --- */
    Route::resource('/parents', StudentParentController::class);
    Route::post('/parents/update-password', [StudentParentController::class, 'updatePassword'])->name('parents.update-password');
    Route::get('/parents/{id}/json', [StudentParentController::class, 'getEdit']);
    Route::get('/parents/{id}/password/json', [StudentParentController::class, 'getEditPassword']);
    Route::get('/available-students/json', [StudentParentController::class, 'getAvailableStudents']);

    /* --- */
    Route::resource('/classrooms', ClassroomController::class);
    Route::get('/classrooms/{id}/json', [ClassroomController::class, 'getEdit']);

    /* --- */
    Route::resource('/users', UserController::class);
    Route::get('/users/{id}/json', [UserController::class, 'getEdit']);

    /* --- */
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/entry-balance', [StudentBalanceController::class, 'entryStudentBalance'])->name('transactions.entry-balance');
    Route::post('/entry-balance', [StudentBalanceController::class, 'storeStudentBalance'])->name('transactions.entry-balance');
    Route::get('/balances/report', [StudentBalanceController::class, 'balanceReport'])->name('transactions.balance-report');
    Route::get('/balances/report/print', [StudentBalanceController::class, 'printBalanceReport'])->name('transactions.balance-report.print');

    /* --- */
    Route::get('/settings', [SettingController::class, 'show'])->name('settings');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');

    /* --- */
    Route::get('/teachers/trash/all', [TeacherController::class, 'trash'])->name('teachers.trash');
    Route::get('/teachers/restore/{id}', [TeacherController::class, 'restore'])->name('teachers.restore');
    Route::delete('/teachers/kill/{id}', [TeacherController::class, 'kill'])->name('teachers.kill');
    Route::get('/classrooms/trash/all', [ClassroomController::class, 'trash'])->name('classrooms.trash');
    Route::get('/classrooms/restore/{id}', [ClassroomController::class, 'restore'])->name('classrooms.restore');
    Route::delete('/classrooms/kill/{id}', [ClassroomController::class, 'kill'])->name('classrooms.kill');
    Route::get('/parents/trash/all', [StudentParentController::class, 'trash'])->name('parents.trash');
    Route::get('/parents/restore/{id}', [StudentParentController::class, 'restore'])->name('parents.restore');
    Route::delete('/parents/kill/{id}', [StudentParentController::class, 'kill'])->name('parents.kill');
    Route::get('/students/trash/all', [StudentController::class, 'trash'])->name('students.trash');
    Route::get('/students/restore/{id}', [StudentController::class, 'restore'])->name('students.restore');
    Route::delete('/students/kill/{id}', [StudentController::class, 'kill'])->name('students.kill');

    Route::patch('/fcm-token', [HomeController::class, 'updateToken'])->name('fcmToken');
    Route::post('/send-notification', [HomeController::class, 'notification'])->name('notification');
});
