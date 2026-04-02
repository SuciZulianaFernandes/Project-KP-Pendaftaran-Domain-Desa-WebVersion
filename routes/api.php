<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PengajuanApiController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('pengajuan')->group(function () {

    Route::post('/check-domain', [PengajuanApiController::class, 'checkDomain']);

    Route::middleware('auth:sanctum')->post('/submit', [PengajuanApiController::class, 'submit']);
});