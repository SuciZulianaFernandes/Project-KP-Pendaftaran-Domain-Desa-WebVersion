<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/check-domain', [DomainController::class, 'checkDomain']);
Route::post('/domains', [DomainController::class, 'store']);