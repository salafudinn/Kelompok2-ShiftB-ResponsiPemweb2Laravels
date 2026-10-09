<?php

use App\Http\Controllers\AssistantAccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\DamageReportController;
use App\Http\Controllers\IoTKitController;
use Illuminate\Support\Facades\Route;

// Auth — Guest
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected — Auth:Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Assistant accounts — only the owner
    Route::middleware('can:manage-assistant-accounts')->group(function () {
        Route::get('/assistant-accounts', [AssistantAccountController::class, 'index']);
        Route::post('/assistant-accounts', [AssistantAccountController::class, 'store']);
        Route::put('/assistant-accounts/{assistant}', [AssistantAccountController::class, 'update']);
        Route::delete('/assistant-accounts/{assistant}', [AssistantAccountController::class, 'destroy']);
    });

    // IoT Kits — semua user bisa lihat
    Route::get('/iot-kits', [IoTKitController::class, 'index']);
    Route::get('/iot-kits/{id}', [IoTKitController::class, 'show']);

    // IoT Kits — hanya asisten_lab
    Route::middleware('can:manage-kits')->group(function () {
        Route::post('/iot-kits', [IoTKitController::class, 'store']);
        Route::put('/iot-kits/{id}', [IoTKitController::class, 'update']);
        Route::delete('/iot-kits/{id}', [IoTKitController::class, 'destroy']);
    });

    // Borrowings — semua user bisa lihat riwayat
    Route::get('/borrowings', [BorrowingController::class, 'index']);

    // Borrowings — mahasiswa mengajukan pinjam
    Route::middleware('can:borrow-kits')->post('/borrowings', [BorrowingController::class, 'store']);

    // Borrowings — asisten_lab mengelola status
    Route::middleware('can:manage-borrowings')->group(function () {
        Route::put('/borrowings/{id}/approve', [BorrowingController::class, 'approve']);
        Route::put('/borrowings/{id}/reject', [BorrowingController::class, 'reject']);
        Route::put('/borrowings/{id}/pickup', [BorrowingController::class, 'pickup']);
        Route::put('/borrowings/{id}/return', [BorrowingController::class, 'returnItem']);
    });

    // Damage Reports
    Route::get('/damage-reports', [DamageReportController::class, 'index']);
    Route::post('/damage-reports', [DamageReportController::class, 'store']);
    Route::middleware('can:manage-reports')->put('/damage-reports/{id}/resolve', [DamageReportController::class, 'resolve']);
});
