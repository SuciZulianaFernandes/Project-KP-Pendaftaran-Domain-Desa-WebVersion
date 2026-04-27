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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ================= USER =================
Route::prefix('pengajuan')->group(function () {
    Route::post('/check-domain', [UserPengajuanController::class, 'checkDomain']);
    Route::post('/submit', [UserPengajuanController::class, 'submit']);
});

// ================= ADMIN =================
Route::prefix('admin')->group(function () {
    Route::get('/pengajuan', [AdminPengajuanController::class, 'index']);
    Route::get('/pengajuan/{id}', [AdminPengajuanController::class, 'show']);
    Route::post('/verifikasi/{id}', [AdminPengajuanController::class, 'verifikasi']);
    Route::post('/aktivasi/{id}', [AktivasiController::class, 'aktivasi']);
});