<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\DinkesController;
use App\Http\Controllers\Dinkes\EvaluationController;
use App\Http\Controllers\Dinkes\ManageOperatorsController;
use App\Http\Controllers\Dinkes\ReportsController;
use App\Http\Controllers\Dinkes\ReportingController;
use App\Http\Controllers\Dinkes\DashboardController;
use App\Http\Controllers\Kecamatan\LaporanUnitController;
use App\Http\Controllers\Kecamatan\SlhsController;
use App\Http\Controllers\Kecamatan\LaporanSasaranController;
use App\Http\Controllers\Kecamatan\DashboardController as KecamatanDashboardController;

Route::get('/', [GuestController::class, 'index'])->name('guest.index');

Route::get('/rekap', [GuestController::class, 'rekap'])->name('guest.rekap');
Route::get('/unit/{unit}', [GuestController::class, 'showUnit'])->whereNumber('unit')->name('guest.sppg.show');
Route::get('/api/rekap/kecamatan/{id?}', [GuestController::class, 'getKecamatanData'])->name('api.rekap.kecamatan');
Route::get('/api/rekap/{type}', [GuestController::class, 'getRekapByKecamatan'])->name('api.rekap.type');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

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
        Route::delete('/reporting/{unit}/delete', [ReportingController::class, 'destroy'])->name('reporting.destroy');
        Route::get('/reporting/ikl/search', [ReportingController::class, 'searchIkl'])
            ->name('reporting.ikl.search');

    });
});

Route::middleware('admin_kecamatan')->prefix('kecamatan')->name('kecamatan.')->group(function () {
    Route::get('/', [KecamatanDashboardController::class, 'index'])->name('dashboard');
    Route::get('/laporan-unit', [LaporanUnitController::class, 'index'])->name('laporan-unit.index');
    Route::get('/laporan-unit/create', [LaporanUnitController::class, 'create'])->name('laporan-unit.create');
    Route::post('/laporan-unit', [LaporanUnitController::class, 'store'])->name('laporan-unit.store');
    Route::get('laporan-unit/ikl/search', [LaporanUnitController::class, 'searchIkl'])->name('laporan-unit.ikl.search');
    Route::get('/laporan-unit/{unit}', [LaporanUnitController::class, 'show'])->name('laporan-unit.show');
    Route::get('/laporan-unit/{unit}/edit', [LaporanUnitController::class, 'edit'])->name('laporan-unit.edit');
    Route::put('/laporan-unit/{unit}',[LaporanUnitController::class, 'update'])->name('laporan-unit.update');
    Route::get('/kelayakan',[SlhsController::class, 'index'])->name('kelayakan.index');
    Route::get('/kelayakan/{unit}', [SlhsController::class, 'showKelayakan'])->name('kelayakan.show');

    Route::get('/sasaran', [LaporanSasaranController::class, 'index'])->name('sasaran.index');
});