<?php

use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:api');

Route::middleware(['auth:api'])->group(function () {
    Route::middleware(['role:admin'])->group(function () {
        /* --- */
        Route::get('/dashboard', [DashboardController::class, 'index']);
        /* --- */
        Route::post('/transactions', [TransactionController::class, 'store']);
        /* --- */
        Route::post('/settings', [SettingController::class, 'store']);
    });

    Route::get('/students/{id}/balance', [StudentController::class, 'getStudentBalance']);
    Route::get('/announcement', [DashboardController::class, 'announcement']);
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/service-charge', [SettingController::class, 'getServiceCharge']);
    Route::get('/settings', [SettingController::class, 'show']);

    Route::middleware(['role:parent'])->group(function () {
        /* --- */
        Route::get('/students', [StudentController::class, 'getStudent']);
        Route::post('/students/{id}/setting', [StudentController::class, 'updateBalanceSetting']);
        Route::post('/entry-balance', [StudentController::class, 'storeStudentBalance']);
        // Route::get('/students/{id}/setting', [StudentController::class, 'getBalanceSetting']);
    });
});

