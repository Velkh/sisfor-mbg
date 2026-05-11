<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\AdminDinkesMiddleware;
use App\Http\Middleware\OperatorSppgMiddleware;
use App\Http\Middleware\AdminKecamatanMiddleware;
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
use App\Http\Controllers\Dinkes\ReportingController;
use App\Http\Controllers\Dinkes\DashboardController;

Route::get('/', [GuestController::class, 'index'])->name('guest.index');

Route::get('/rekap', [GuestController::class, 'rekap'])->name('guest.rekap');
Route::get('/sppg/{sppg}', [GuestController::class, 'showSppg'])->whereNumber('sppg')->name('guest.sppg.show');
Route::get('/api/rekap/kecamatan/{id?}', [GuestController::class, 'getKecamatanData'])->name('api.rekap.kecamatan');
Route::get('/api/rekap/{type}', [GuestController::class, 'getRekapByKecamatan'])->name('api.rekap.type');//

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
        Route::get('/kelayakan', [DinkesController::class, 'kelayakan'])->name('kelayakan');
        
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
        Route::get('/data/export/excel', [EvaluationController::class, 'exportExcel'])->name('data.export.excel');
        Route::get('/reporting', [ReportingController::class, 'index'])->name('reporting.index');
        Route::get('/reporting/create', [ReportingController::class, 'create'])->name('reporting.create');
        Route::post('/reporting', [ReportingController::class, 'store'])->name('reporting.store');
        Route::get('/reporting/{unit}', [ReportingController::class, 'show'])->name('reporting.show');
        Route::get('/reporting/{unit}/edit', [ReportingController::class, 'edit'])->name('reporting.edit');
        Route::put('/reporting/{unit}', [ReportingController::class, 'update'])->name('reporting.update');
        Route::delete('/reporting/{unit}/sasaran/{sasaran}', [ReportingController::class, 'destroySasaran'])
            ->name('reporting.sasaran.destroy');
        Route::get('/reporting/ikl/search', [ReportingController::class, 'searchIkl'])
            ->name('reporting.ikl.search');

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

Route::middleware('admin_kecamatan')->prefix('kecamatan')->name('kecamatan.')->group(function () {
   
});