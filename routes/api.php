<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\PresensiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\API\IzinController;

// Rute untuk autentikasi
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Rute untuk pengajuan izin dengan autentikasi
Route::middleware('auth:sanctum')->group(function () {
    // Rute untuk mengelola izin
    Route::get('/izin', [IzinController::class, 'index']);
    Route::post('/izin/store', [IzinController::class, 'store']);
    Route::put('/izin/update/{id}', [IzinController::class, 'update']);
    Route::delete('/izin/delete/{id}', [IzinController::class, 'destroy']);
    Route::patch('/izin/approve/{id}', [IzinController::class, 'approve']); // Menggunakan PATCH
    Route::patch('/izin/reject/{id}', [IzinController::class, 'reject']);   // Menggunakan PATCH
    
    // Rute untuk mendapatkan daftar presensi
    Route::get('/get-presensi', [PresensiController::class, 'getPresensis']);
    
    // Rute untuk menyimpan presensi dengan validasi lokasi
    Route::post('/save-presensi', [PresensiController::class, 'store'])->middleware('validate.location');
    
    // Rute untuk rekap absen
    Route::get('/rekap-absen', [UserController::class, 'rekapAbsen']);
});

// Rute untuk absensi masuk
Route::post('/absen-masuk', [PresensiController::class, 'store'])->middleware('auth:sanctum');

// Rute untuk absensi pulang
Route::post('/absen-pulang', [PresensiController::class, 'store'])->middleware('auth:sanctum');

// Rute tanpa autentikasi yang memerlukan verifikasi lokasi
Route::post('/verify-location', [PresensiController::class, 'verifyLocation']);

// Rute untuk cek presensi
Route::get('/check-presensi', [PresensiController::class, 'checkPresensi']);
