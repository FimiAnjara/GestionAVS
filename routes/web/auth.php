<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Routes d'authentification (sans middleware JWT)
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('jwt');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('auth.refresh')->middleware('jwt');
    Route::get('/me', [AuthController::class, 'me'])->name('auth.me')->middleware('jwt');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');
