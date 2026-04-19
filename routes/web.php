<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\AdminDinkesMiddleware;
use App\Http\Middleware\OperatorSppgMiddleware;
use App\Http\Controllers\DinkesController;
use App\Http\Controllers\Sppg\SppgController;
Route::get('/', function () {
    return view('homepage');
})->name('home');

// Login routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Admin Dinkes Routes
    Route::middleware('admin_dinkes')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DinkesController::class, 'index'])->name('dashboard');
        Route::get('/kelola', [DinkesController::class, 'kelola'])->name('kelola');
        Route::get('/data', [DinkesController::class, 'kelayakan'])->name('data');
        Route::get('/laporan', [DinkesController::class, 'laporan'])->name('laporan');
    });
});

// Operator SPPG Routes
Route::middleware('operator_sppg')->prefix('sppg')->name('sppg.')->group(function () {
    Route::get('/dashboard', [SppgController::class, 'index'])->name('index');
    Route::get('/inspeksi', [SppgController::class, 'inspeksi'])->name('inspeksi');
    Route::get('/profile', [SppgController::class, 'profile'])->name('profile');
    Route::get('/suratlaik', [SppgController::class, 'suratlaik'])->name('suratlaik');
    Route::get('/pelaporan', [SppgController::class, 'pelaporan'])->name('pelaporan');
});