<?php

use App\Http\Controllers\CaisseController;
use App\Http\Controllers\MvtCaisseController;
use App\Http\Controllers\TypeEvaluationStockController;
use Illuminate\Support\Facades\Route;

// Routes Caisse (Finance)
Route::prefix('caisse')->group(function () {
    Route::get('/list', [CaisseController::class, 'list'])->name('caisse.list');
    Route::get('/create', [CaisseController::class, 'create'])->name('caisse.create');
    Route::post('/', [CaisseController::class, 'store'])->name('caisse.store');
    Route::get('/{id}', [CaisseController::class, 'show'])->name('caisse.show');
    Route::get('/{id}/edit', [CaisseController::class, 'edit'])->name('caisse.edit');
    Route::put('/{id}', [CaisseController::class, 'update'])->name('caisse.update');
    Route::delete('/{id}', [CaisseController::class, 'destroy'])->name('caisse.destroy');
});

// Routes Mouvements Caisse
Route::prefix('mvt-caisse')->group(function () {
    Route::get('/list', [MvtCaisseController::class, 'list'])->name('mvt-caisse.list');
    Route::get('/create', [MvtCaisseController::class, 'create'])->name('mvt-caisse.create');
    Route::get('/create-from-facture/{id}', [MvtCaisseController::class, 'createFromFacture'])->name('mvt-caisse.createFromFacture');
    Route::post('/', [MvtCaisseController::class, 'store'])->name('mvt-caisse.store');
    Route::get('/{id}', [MvtCaisseController::class, 'show'])->name('mvt-caisse.show');
    Route::get('/{id}/edit', [MvtCaisseController::class, 'edit'])->name('mvt-caisse.edit');
    Route::put('/{id}', [MvtCaisseController::class, 'update'])->name('mvt-caisse.update');
    Route::delete('/{id}', [MvtCaisseController::class, 'destroy'])->name('mvt-caisse.destroy');
    Route::get('/etat/rapport', [MvtCaisseController::class, 'etat'])->name('mvt-caisse.etat');
    Route::get('/etat/export-pdf', [MvtCaisseController::class, 'exportPdf'])->name('mvt-caisse.export-pdf');
});

// Routes Types d'Évaluation de Stock
Route::prefix('type-evaluation-stock')->group(function () {
    Route::get('/list', [TypeEvaluationStockController::class, 'index'])->name('type-evaluation-stock.list');
    Route::get('/create', [TypeEvaluationStockController::class, 'create'])->name('type-evaluation-stock.create');
    Route::post('/', [TypeEvaluationStockController::class, 'store'])->name('type-evaluation-stock.store');
    Route::get('/{id}', [TypeEvaluationStockController::class, 'show'])->name('type-evaluation-stock.show');
    Route::get('/{id}/edit', [TypeEvaluationStockController::class, 'edit'])->name('type-evaluation-stock.edit');
    Route::put('/{id}', [TypeEvaluationStockController::class, 'update'])->name('type-evaluation-stock.update');
    Route::delete('/{id}', [TypeEvaluationStockController::class, 'destroy'])->name('type-evaluation-stock.destroy');
});
