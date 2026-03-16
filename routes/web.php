<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PengajuanController;


Route::get('/', function () {
    return view('guest.homepage');
});

Route::get('/register',[RegisterController::class,'showRegister']);
Route::post('/register', [RegisterController::class,'register'])->name('register');
Route::get('/login',[LoginController::class,'showLogin'])->name('login');
Route::post('/login',[LoginController::class,'login']);
Route::get('/desa/dashboard', function () {
    return view('desa.dashboard');
});

Route::get('/desa/pengajuan', [PengajuanController::class,'index']);
Route::post('/cek-domain', [PengajuanController::class,'cekDomain'])->name('cek.domain');
Route::post('/api/check-domain-availability', [PengajuanController::class, 'checkAvailabilityApi'])->name('api.check.domain');
