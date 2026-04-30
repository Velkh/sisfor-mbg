<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\AdminDinkesMiddleware;
use App\Http\Middleware\OperatorSppgMiddleware;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\DinkesController;
use App\Http\Controllers\Sppg\SppgController;
use App\Http\Controllers\Sppg\DaftarSppgController;
use App\Http\Controllers\Sppg\IklController;
use App\Http\Controllers\Sppg\SlhsController;
use App\Http\Controllers\Sppg\DistribusiController;
use App\Http\Controllers\Dinkes\EvaluationController;
use App\Http\Controllers\Dinkes\ManageOperatorsController;
use App\Http\Controllers\Dinkes\ReportsController;
use App\Http\Controllers\Dinkes\DashboardController;

Route::get('/', [GuestController::class, 'home'])->name('home');
Route::get('/rekap', [GuestController::class, 'rekap'])->name('guest.rekap');
Route::get('/sppg/{sppg}', [GuestController::class, 'showSppg'])->whereNumber('sppg')->name('guest.sppg.show');
Route::get('/api/rekap/kecamatan/{id?}', [GuestController::class, 'getKecamatanData'])->name('api.rekap.kecamatan');
Route::get('/api/rekap/{type}', [GuestController::class, 'getRekapByKecamatan'])->name('api.rekap.type');
// Login routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Admin Dinkes Routes
    Route::middleware('admin_dinkes')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/kelola', [DinkesController::class, 'kelola'])->name('kelola');
        
        Route::get('/laporan', [ReportsController::class, 'index'])->name('laporan');

        Route::get('/manage-operator', [ManageOperatorsController::class, 'index'])->name('manage.index');
        Route::get('/manage-operator/create', [ManageOperatorsController::class, 'create'])->name('manage.create');
        Route::post('/manage-operator', [ManageOperatorsController::class, 'store'])->name('manage.store');
        Route::get('/manage-operator/{operator}', [ManageOperatorsController::class, 'show'])->name('manage.show');
        Route::get('/manage-operator/{operator}/edit', [ManageOperatorsController::class, 'edit'])->name('manage.edit');
        Route::put('/manage-operator/{operator}', [ManageOperatorsController::class, 'update'])->name('manage.update');
        Route::delete('/manage-operator/{operator}', [ManageOperatorsController::class, 'destroy'])->name('manage.destroy');

        Route::get('/data', [EvaluationController::class, 'index'])->name('data');
        Route::get('/data/{sppg}', [EvaluationController::class, 'show'])->name('data.show');
        Route::get('/data/export/pdf', [EvaluationController::class, 'exportPdf'])->name('data.export.pdf');


    });
});

// Operator SPPG Routes
Route::middleware('operator_sppg')->prefix('sppg')->name('sppg.')->group(function () {
    Route::get('/dashboard', [SppgController::class, 'index'])->name('index');

    // Halaman inspeksi (UI)
    Route::get('/inspeksi', [SppgController::class, 'inspeksi'])->name('inspeksi');

    // Aksi IKL (API search + simpan)
    Route::get('/ikl/search', [IklController::class, 'search'])->name('ikl.search');
    Route::post('/ikl', [IklController::class, 'store'])->name('ikl.store');

    // Daftar / Profile SPPG
    Route::get('/profile', [DaftarSppgController::class, 'create'])->name('profile');
    Route::get('/daftar', [DaftarSppgController::class, 'create'])->name('daftar.create');
    Route::post('/daftar', [DaftarSppgController::class, 'store'])->name('daftar.store');

    Route::get('/suratlaik', [SppgController::class, 'suratlaik'])->name('suratlaik');
    Route::post('/suratlaik', [SlhsController::class, 'store'])->name('suratlaik.store');

    Route::get('/pelaporan', [SppgController::class, 'pelaporan'])->name('pelaporan');
    Route::post('/pelaporan', [DistribusiController::class, 'store'])->name('pelaporan.store');
});