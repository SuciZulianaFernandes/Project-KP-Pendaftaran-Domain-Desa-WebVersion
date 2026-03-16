<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\Admin\ProfileController;



Route::get('/', function () {
    return view('guest.homepage');
});

Route::get('/register',[RegisterController::class,'showRegister']);
Route::post('/register', [RegisterController::class,'register'])->name('register');
Route::get('/login',[LoginController::class,'showLogin'])->name('login');
Route::post('/login',[LoginController::class,'login']);
Route::post('/logout',[LoginController::class,'logout'])->name('logout');



Route::middleware(['auth'])->group(function(){
    Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});
Route::get('/admin/profile',[ProfileController::class,'edit']);
Route::post('/admin/profile',[ProfileController::class,'update']);
});

Route::get('/desa/dashboard', function () {
    return view('desa.dashboard');
});

Route::middleware(['auth'])->group(function(){
    Route::get('/desa/dashboard', function () {
    return view('desa.dashboard');
});
Route::get('/desa/pengajuan', [PengajuanController::class,'index'])->name('desa.pengajuan');
Route::post('/cek-domain', [PengajuanController::class,'cekDomain'])->name('cek.domain');
Route::post('/api/check-domain-availability', [PengajuanController::class, 'checkAvailabilityApi'])->name('api.check.domain');
});