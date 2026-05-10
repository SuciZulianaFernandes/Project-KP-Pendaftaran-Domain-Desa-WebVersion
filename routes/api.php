<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PengajuanApiController as UserPengajuanController;

use App\Http\Controllers\Admin\PengajuanApiController as AdminPengajuanController;
use App\Http\Controllers\Admin\AktivasiController;

// ================= AUTH =================
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/profile', [AuthController::class, 'profile']);
Route::post('/profile/update', [AuthController::class, 'updateProfile']);

Route::post('/instansi', [AuthController::class, 'instansi']);
Route::post('/instansi/update', [AuthController::class, 'updateInstansi']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ================= USER / DESA =================
Route::prefix('pengajuan')->group(function () {
    // CEK DOMAIN
    Route::post('/check-domain', [UserPengajuanController::class, 'checkDomain']);

    // SUBMIT PENGAJUAN BARU
    Route::post('/submit', [UserPengajuanController::class, 'submit']);

    // DATA PENGAJUAN USER UNTUK VERIFIKASI DOKUMEN MOBILE
    Route::post('/user', [UserPengajuanController::class, 'getPengajuanUser']);

    // OPSIONAL UNTUK HALAMAN LAMA
    Route::post('/riwayat', [UserPengajuanController::class, 'getPengajuanUser']);

    // UPDATE PENGAJUAN SAAT STATUS PERLU PERBAIKAN
    Route::post('/update/{id}', [UserPengajuanController::class, 'update']);

    // UPLOAD BUKTI PEMBAYARAN KE TABEL FAKTURS
    Route::post('/bukti-pembayaran/{id}', [UserPengajuanController::class, 'uploadBuktiPembayaran']);
});

// ================= ADMIN =================
Route::prefix('admin')->group(function () {
    // LIST PENGAJUAN ADMIN
    Route::get('/pengajuan', [AdminPengajuanController::class, 'index']);

    // DETAIL PENGAJUAN ADMIN
    Route::get('/pengajuan/{id}', [AdminPengajuanController::class, 'show']);

    // VERIFIKASI DOKUMEN ADMIN
    Route::post('/verifikasi/{id}', [AdminPengajuanController::class, 'verifikasi']);

    // AKTIVASI DOMAIN ADMIN
    Route::post('/admin/verifikasi/{id}',[AdminPengajuanController::class, 'verifikasi']);
    Route::post('/admin/aktivasi/proses/{id}',[AdminPengajuanController::class, 'aktivasi']);
});