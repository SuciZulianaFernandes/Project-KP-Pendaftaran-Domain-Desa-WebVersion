<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PengajuanApiController as UserPengajuanController;
use App\Http\Controllers\Api\PesanControllerApi;
use App\Http\Controllers\Api\DomainTerdaftarController;


use App\Http\Controllers\Admin\PengajuanApiController as AdminPengajuanController;


// ================= AUTH =================
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


// ================= ROUTE LOGIN =================
Route::middleware('auth:sanctum')->group(function () {

    // PROFILE
    Route::post('/profile', [AuthController::class, 'profile']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);

    // INSTANSI
    Route::post('/instansi', [AuthController::class, 'instansi']);
    Route::post('/instansi/update', [AuthController::class, 'updateInstansi']);

    // USER LOGIN
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // ================= USER / DESA =================
    Route::prefix('pengajuan')->group(function () {

        Route::post('/check-domain',
            [UserPengajuanController::class, 'checkDomain']);

        Route::post('/submit',
            [UserPengajuanController::class, 'submit']);

        Route::post('/user',
            [UserPengajuanController::class, 'getPengajuanUser']);

        Route::post('/riwayat',
            [UserPengajuanController::class, 'getPengajuanUser']);

        Route::post('/update/{id}',
            [UserPengajuanController::class, 'update']);

        Route::post('/bukti-pembayaran/{id}',
            [UserPengajuanController::class, 'uploadBuktiPembayaran']);

        Route::post('/{id}/lanjutkan-pembayaran',
            [UserPengajuanController::class, 'lanjutkanPembayaran']);
    });

    // ================= NOTIFIKASI =================
    Route::get('/notifikasi',[PesanControllerApi::class, 'index']);
        
    Route::get('/domain-terdaftar', [DomainTerdaftarController::class, 'index']);
    });

// ================= ADMIN =================
Route::prefix('admin')->group(function () {

    Route::get('/pengajuan',
        [AdminPengajuanController::class, 'index']);

    Route::get('/pengajuan/{id}',
        [AdminPengajuanController::class, 'show']);

    Route::post('/verifikasi/{id}',
        [AdminPengajuanController::class, 'verifikasi']);

    Route::get('/pembayaran',
        [AdminPengajuanController::class, 'pembayaran']);

    Route::post('/verifikasi-pembayaran/{id}',
        [AdminPengajuanController::class, 'verifikasiPembayaran']);

    Route::post('/aktivasi/proses/{id}',
        [AdminPengajuanController::class, 'aktivasi']);

    Route::get('/faktur',[AdminPengajuanController::class, 'fakturMobile']);

    Route::get('/faktur/{id}',[AdminPengajuanController::class, 'detailFakturMobile']);


});