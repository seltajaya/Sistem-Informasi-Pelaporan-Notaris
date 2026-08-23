<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\NotarisController;
use App\Http\Controllers\Admin\RecapController;
use App\Http\Controllers\Admin\RegionAdminController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'notaris'])
    ->name('dashboard');

Route::middleware(['auth', 'notaris'])->group(function () {
    Route::get('/laporan/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/laporan', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/laporan/{report}/download', [ReportController::class, 'download'])->name('reports.download');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->middleware(['auth', 'verified'])->name('admin.')->group(function () {
    // Rute yang dapat diakses oleh semua admin (admin_wilayah dan superadmin)
    Route::middleware(['admin'])->group(function () {
        Route::get('/notaris', [NotarisController::class, 'index'])->name('notaris.index');
        Route::post('/notaris', [NotarisController::class, 'store'])->name('notaris.store');
        Route::get('/kepatuhan', [RecapController::class, 'tracking'])->name('recap.tracking');
    });

    // Rute yang hanya dapat diakses oleh superadmin
    Route::middleware(['superadmin'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/laporan', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('/laporan/{report}', [AdminReportController::class, 'show'])->name('reports.show');
        Route::get('/laporan/{report}/download', [AdminReportController::class, 'download'])->name('reports.download');

        Route::get('/rekapitulasi', [RecapController::class, 'annual'])->name('recap.annual');
        Route::get('/rekapitulasi/{year}', [RecapController::class, 'monthly'])->name('recap.monthly');

        Route::get('/admin-wilayah', [RegionAdminController::class, 'index'])->name('region-admins.index');
        Route::post('/admin-wilayah', [RegionAdminController::class, 'store'])->name('region-admins.store');
        Route::delete('/admin-wilayah/{user}', [RegionAdminController::class, 'destroy'])->name('region-admins.destroy');
    });
});

require __DIR__.'/auth.php';

Route::get('/login/{slug?}', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login/{slug?}', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::get('/admin/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('admin.login');

Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');
