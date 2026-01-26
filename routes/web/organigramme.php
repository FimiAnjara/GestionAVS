<?php

use App\Http\Controllers\GroupeController;
use App\Http\Controllers\EntiteController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Routes Organigramme - Groupe
Route::prefix('groupe')->group(function () {
    Route::get('/list', [GroupeController::class, 'list'])->name('groupe.list');
    Route::get('/create', [GroupeController::class, 'create'])->name('groupe.create');
    Route::post('/', [GroupeController::class, 'store'])->name('groupe.store');
    Route::get('/{id}', [GroupeController::class, 'show'])->name('groupe.show');
    Route::get('/{id}/edit', [GroupeController::class, 'edit'])->name('groupe.edit');
    Route::put('/{id}', [GroupeController::class, 'update'])->name('groupe.update');
    Route::delete('/{id}', [GroupeController::class, 'destroy'])->name('groupe.destroy');
});

// Routes Organigramme - Entite
Route::prefix('entite')->group(function () {
    Route::get('/list', [EntiteController::class, 'list'])->name('entite.list');
    Route::get('/create', [EntiteController::class, 'create'])->name('entite.create');
    Route::post('/', [EntiteController::class, 'store'])->name('entite.store');
    Route::get('/{id}', [EntiteController::class, 'show'])->name('entite.show');
    Route::get('/{id}/edit', [EntiteController::class, 'edit'])->name('entite.edit');
    Route::put('/{id}', [EntiteController::class, 'update'])->name('entite.update');
    Route::delete('/{id}', [EntiteController::class, 'destroy'])->name('entite.destroy');
});

// Routes Organigramme - Site
Route::prefix('site')->group(function () {
    Route::get('/list', [SiteController::class, 'list'])->name('site.list');
    Route::get('/create', [SiteController::class, 'create'])->name('site.create');
    Route::post('/', [SiteController::class, 'store'])->name('site.store');
    Route::get('/{id}', [SiteController::class, 'show'])->name('site.show');
    Route::get('/{id}/edit', [SiteController::class, 'edit'])->name('site.edit');
    Route::put('/{id}', [SiteController::class, 'update'])->name('site.update');
    Route::delete('/{id}', [SiteController::class, 'destroy'])->name('site.destroy');
});

// Dashboard Global - Directeur Général
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/dashboard/global', [DashboardController::class, 'global'])->name('dashboard.global');
Route::get('/api/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
