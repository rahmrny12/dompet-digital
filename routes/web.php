<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\StudentBalanceController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StudentParentController;
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
    Route::get('/pengaturan/profile', [UserController::class, 'edit_profile'])->name('pengaturan.profile');
    Route::post('/pengaturan/ubah-profile', [UserController::class, 'ubah_profile'])->name('pengaturan.ubah-profile');

    /* --- */
    Route::get('/students/classrooms', [StudentController::class, 'classrooms'])->name('students.classrooms');
    Route::get('/students/classrooms/{id}', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
    Route::post('/students/setting', [StudentController::class, 'updateBalanceSetting'])->name('students.update-setting');

    Route::get('/students/{id}/json', [StudentController::class, 'getEdit']);
    Route::get('/classrooms/{id}/students', [StudentController::class, 'getStudentsByClassroom']);
    Route::get('/students/{id}/setting', [StudentController::class, 'getBalanceSetting']);

    /* --- */
    Route::resource('/teachers', TeacherController::class);
    Route::get('/teachers/{id}/json', [TeacherController::class, 'getEdit']);

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
    Route::resource('/admins', AdminController::class);
    Route::get('/admins/{id}/json', [AdminController::class, 'getEdit']);

    /* --- */
    Route::get('/transactions/classrooms', [TransactionController::class, 'classrooms'])->name('transactions.classrooms');
    Route::get('/transactions/classrooms/{id}', [TransactionController::class, 'index'])->name('transactions');
    Route::get('/entry-balance', [StudentBalanceController::class, 'entryStudentBalance'])->name('transactions.entry-balance');
    Route::post('/entry-balance', [StudentBalanceController::class, 'storeStudentBalance'])->name('transactions.entry-balance');

    /* --- */
    Route::get('/settings', [SettingController::class, 'show'])->name('settings');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
});
