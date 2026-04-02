
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PesanController;
use App\Http\Controllers\FakturController;

// Route untuk tamu (guest)
Route::get('/', function () {
    return view('guest.homepage');
});

// Route Autentikasi
Route::get('/register', [RegisterController::class, 'showRegister']);
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// =====================================================
// ROUTE UNTUK ADMIN (Memerlukan login dan role admin)
// =====================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    

    // Dashboard Admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Manajemen Profile Instansi
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // --- MANAJEMEN USER ---
    // Karena berada di dalam grup dengan prefix 'admin', route ini akan menjadi /admin/users
    Route::resource('users', UserController::class);

    // MANAJEMEN PENGAJUAN
Route::get('/pengajuan', [PengajuanController::class, 'adminIndex'])
    ->name('pengajuan.index');

Route::get('/pengajuan/{id}', [PengajuanController::class, 'adminDetail'])
    ->name('pengajuan.detail');

// PROSES VERIFIKASI
Route::put('pengajuan/verifikasi/{id}', [PengajuanController::class, 'verifikasi'])
    ->name('verifikasi.proses');

    Route::get('/faktur', [FakturController::class, 'index'])
    ->name('faktur.index');
    Route::post('/faktur/{id}', [FakturController::class, 'store'])
    ->name('faktur.store');
    Route::get('/pesan', [PesanController::class, 'adminIndex'])
    ->name('pesan.index');
    
});


// =====================================================
// ROUTE UNTUK DESA (Memerlukan login dan role desa)
// =====================================================
Route::middleware(['auth', 'role:desa'])->prefix('desa')->name('desa.')->group(function () {
    
    // Dashboard Desa
    Route::get('/dashboard', function () {
        return view('desa.dashboard');
    })->name('dashboard');

    // Route Pengajuan Domain
    Route::post('/cek-domain', [PengajuanController::class, 'cekDomain'])->name('cek.domain');
    Route::post('/api/check-domain-availability', [PengajuanController::class, 'checkAvailabilityApi'])->name('api.check.domain');
    
    Route::prefix('pengajuan')->name('pengajuan.')->group(function () {
        Route::get('/', [PengajuanController::class, 'index'])->name('index');   
        Route::get('/informasi', [PengajuanController::class, 'showInformasiForm'])->name('informasi');
        Route::post('/informasi', [PengajuanController::class, 'storeInformasiForm'])->name('informasi.store');
        Route::get('/dokumen', [PengajuanController::class, 'showDokumenForm'])->name('dokumen');
        Route::post('/dokumen', [PengajuanController::class, 'storeDokumenForm'])->name('dokumen.store');
        Route::get('/tinjau', [PengajuanController::class, 'showTinjauForm'])->name('tinjau');
        Route::post('/submit', [PengajuanController::class, 'submitPengajuan'])->name('submit');
    });
    
    // Route Verifikasi Dokumen
    Route::prefix('verifikasi')->name('verifikasi.')->group(function () {
        Route::get('/', [PengajuanController::class, 'daftar'])->name('daftar');
        Route::get('/{id}', [PengajuanController::class, 'show'])->name('detail');
        Route::delete('/{id}', [PengajuanController::class, 'destroy'])->name('destroy');
        
    });

    Route::put('/verifikasi/dokumen/{id}', [PengajuanController::class, 'updateDokumen'])
        ->name('verifikasi.updateDokumen');
        
        Route::get('/pesan', [PesanController::class, 'index'])
    ->name('pesan.index');

    Route::post('/konfirmasi-pembayaran/{id}', [PesanController::class, 'konfirmasiPembayaran'])
    ->name('konfirmasi.pembayaran');
});

