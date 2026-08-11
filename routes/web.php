<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RecapController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/laporan/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/laporan', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/laporan/{report}/download', [ReportController::class, 'download'])->name('reports.download');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->middleware(['auth', 'verified', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/laporan', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/laporan/{report}', [AdminReportController::class, 'show'])->name('reports.show');
    Route::get('/laporan/{report}/download', [AdminReportController::class, 'download'])->name('reports.download');

    Route::get('/rekapitulasi', [RecapController::class, 'annual'])->name('recap.annual');
    Route::get('/rekapitulasi/{year}', [RecapController::class, 'monthly'])->name('recap.monthly');
    Route::get('/kepatuhan', [RecapController::class, 'tracking'])->name('recap.tracking');
});

require __DIR__.'/auth.php';
