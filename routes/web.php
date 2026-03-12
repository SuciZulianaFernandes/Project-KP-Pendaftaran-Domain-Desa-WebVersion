<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/register',[RegisterController::class,'showRegister']);
Route::post('/register', [RegisterController::class,'register'])->name('register');
Route::get('/login',[LoginController::class,'showLogin'])->name('login');
Route::post('/login',[LoginController::class,'login']);
Route::get('/desa/dashboard', function () {
    return view('desa.dashboard');
});
