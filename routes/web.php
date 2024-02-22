<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\StudentBalanceController;
use App\Http\Controllers\TransactionController;
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
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/pengaturan/profile', [UserController::class, 'edit_profile'])->name('pengaturan.profile');
    Route::post('/pengaturan/ubah-profile', [UserController::class, 'ubah_profile'])->name('pengaturan.ubah-profile');

    /* --- */
    Route::get('/students/classrooms', [StudentController::class, 'classrooms'])->name('students.classrooms');
    Route::get('/students/classrooms/{id}', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');

    Route::get('/students/{id}/json', [StudentController::class, 'getEdit']);
    Route::get('/classrooms/{id}/students', [StudentController::class, 'getStudentsByClassroom']);
    Route::get('/students/{id}/setting', [StudentController::class, 'getBalanceSetting']);

    /* --- */
    Route::resource('/teachers', TeacherController::class);
    Route::get('/teachers/{id}/json', [TeacherController::class, 'getEdit']);

    /* --- */
    Route::resource('/classrooms', ClassroomController::class);
    Route::get('/classrooms/{id}/json', [ClassroomController::class, 'getEdit']);

    /* --- */
    Route::get('/transactions/classrooms', [TransactionController::class, 'classrooms'])->name('transactions.classrooms');
    Route::get('/transactions/classrooms/{id}', [TransactionController::class, 'index'])->name('transactions');
    Route::get('/entry-balance', [StudentBalanceController::class, 'entryStudentBalance'])->name('transactions.entry-balance');
    Route::post('/entry-balance', [StudentBalanceController::class, 'storeStudentBalance'])->name('transactions.entry-balance');
});
