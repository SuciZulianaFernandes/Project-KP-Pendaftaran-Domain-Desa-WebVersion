<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PengajuanApiController as UserPengajuanController;
use App\Http\Controllers\Api\PesanControllerApi;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DomainTerdaftarController;
use App\Http\Controllers\Api\PerpanjanganApiController;

use App\Http\Controllers\Admin\PengajuanApiController as AdminPengajuanController;

// ================= PUBLIC =================
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get(
    '/domain-terdaftar',
    [DomainTerdaftarController::class, 'index']
);

// ================= AUTHENTICATED =================
Route::middleware('auth:sanctum')->group(function () {

    // ================= USER INFO =================
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // ================= PROFILE =================
    Route::prefix('profile')->group(function () {

        Route::post('/', [AuthController::class, 'profile']);

        Route::post('/update',
            [AuthController::class, 'updateProfile']);
    });

    // ================= INSTANSI =================
    Route::prefix('instansi')->group(function () {

        Route::post('/', [AuthController::class, 'instansi']);

        Route::post('/update',
            [AuthController::class, 'updateInstansi']);
    });

    // ================= PENGAJUAN USER =================
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
            
        Route::get('/detail-faktur/{id}',
        [UserPengajuanController::class, 'detailFakturPengajuan']);

        Route::post('/bukti-pembayaran/{id}',
            [UserPengajuanController::class, 'uploadBuktiPembayaran']);

        Route::post('/{id}/lanjutkan-pembayaran',
            [UserPengajuanController::class, 'lanjutkanPembayaran']);
    });

    // ================= PERPANJANGAN USER =================
    Route::prefix('perpanjangan')->group(function () {

        Route::get('/domain',
            [PerpanjanganApiController::class, 'listDomain']);

        Route::post('/ajukan/{id}',
            [PerpanjanganApiController::class, 'ajukan']);

        Route::get('/detail-faktur/{id}',
            [PerpanjanganApiController::class, 'detailFaktur']);

        Route::post('/upload-bukti/{id}',
            [PerpanjanganApiController::class, 'uploadBukti']);

        Route::get('/reminder',
            [PerpanjanganApiController::class, 'cekReminder']);
    });

    // ================= NOTIFIKASI USER =================
    Route::get('/notifikasi',
        [PesanControllerApi::class, 'index']);

    // =====================================================
    // ================= ADMIN ONLY ========================
    // =====================================================

    Route::middleware('api.role:admin')->prefix('admin')->group(function () {

        // ================= DASHBOARD =================
        Route::get('/dashboard',
            [DashboardController::class, 'index']);

        // ================= PENGAJUAN =================
        Route::prefix('pengajuan')->group(function () {

            Route::get('/',
                [AdminPengajuanController::class, 'index']);

            Route::get('/{id}',
                [AdminPengajuanController::class, 'show']);
        });

        // ================= VERIFIKASI =================
        Route::post('/verifikasi/{id}',
            [AdminPengajuanController::class, 'verifikasi']);

        Route::get('/pembayaran',
            [AdminPengajuanController::class, 'pembayaran']);

        Route::post('/verifikasi-pembayaran/{id}',
            [AdminPengajuanController::class, 'verifikasiPembayaran']);

        Route::post('/aktivasi/proses/{id}',
            [AdminPengajuanController::class, 'aktivasi']);

        // ================= FAKTUR =================
        Route::get('/faktur',
            [AdminPengajuanController::class, 'fakturMobile']);

        Route::get('/faktur/{id}',
            [AdminPengajuanController::class, 'detailFakturMobile']);

        // ================= NOTIFIKASI ADMIN =================
        Route::get('/notifikasi',
            [PesanControllerApi::class, 'adminNotif']);

        // ================= PERPANJANGAN ADMIN =================
        Route::prefix('perpanjangan')->group(function () {

            Route::get('/list',
                [PerpanjanganApiController::class, 'adminList']);

            Route::post('/buat-faktur/{id}',
                [PerpanjanganApiController::class, 'buatFaktur']);

            Route::post('/verifikasi/{id}',
                [PerpanjanganApiController::class, 'verifikasiPembayaran']);

            Route::post('/aktivasi/{id}',
                [PerpanjanganApiController::class, 'aktivasi']);

            Route::get('/list-faktur',
                [PerpanjanganApiController::class, 'adminListFaktur']);
        });
    });
});