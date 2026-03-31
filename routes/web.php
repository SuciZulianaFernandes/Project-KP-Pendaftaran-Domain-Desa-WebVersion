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

Route::middleware(['auth'])->group(function(){
    Route::get('/desa/dashboard', function () {
    return view('desa.dashboard');
});
Route::post('/cek-domain', [PengajuanController::class,'cekDomain'])->name('cek.domain');
Route::post('/api/check-domain-availability', [PengajuanController::class, 'checkAvailabilityApi'])->name('api.check.domain');
Route::prefix('desa/pengajuan')->name('pengajuan.')->group(function () {
Route::get('/', [PengajuanController::class,'index'])->name('index');   
// Langkah 2: Informasi Desa
    Route::get('/informasi', [PengajuanController::class, 'showInformasiForm'])->name('informasi');
    Route::post('/informasi', [PengajuanController::class, 'storeInformasiForm'])->name('informasi.store');

    // Langkah 3: Upload Dokumen
    Route::get('/dokumen', [PengajuanController::class, 'showDokumenForm'])->name('dokumen');
    Route::post('/dokumen', [PengajuanController::class, 'storeDokumenForm'])->name('dokumen.store');
    
    // Langkah 4: Tinjau & Submit
    Route::get('/tinjau', [PengajuanController::class, 'showTinjauForm'])->name('tinjau');
    Route::post('/submit', [PengajuanController::class, 'submitPengajuan'])->name('submit');
});
Route::prefix('desa/verifikasi')->name('verifikasi.')->group(function () {
    Route::get('/', [PengajuanController::class, 'daftar'])->name('daftar');
    Route::get('/{id}', [PengajuanController::class, 'show'])->name('detail');
    Route::delete('/{id}', [PengajuanController::class, 'destroy'])->name('destroy');
});

Route::put('/desa/verifikasi/dokumen/{id}', [PengajuanController::class, 'updateDokumen'])
    ->name('verifikasi.updateDokumen');
});


