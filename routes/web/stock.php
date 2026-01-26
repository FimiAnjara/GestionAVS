<?php

use App\Http\Controllers\BonReceptionController;
use App\Http\Controllers\MvtStockController;
use App\Http\Controllers\MagasinController;
use Illuminate\Support\Facades\Route;

// Routes Bon de Réception
Route::prefix('bon-reception')->group(function () {
    Route::get('/list', [BonReceptionController::class, 'list'])->name('bon-reception.list');
    Route::get('/create', [BonReceptionController::class, 'create'])->name('bon-reception.create');
    Route::get('/api/bon-commande/{id}', [BonReceptionController::class, 'getBonCommandeData'])->name('bon-reception.api.bon-commande');
    Route::post('/', [BonReceptionController::class, 'store'])->name('bon-reception.store');
    Route::get('/{id}', [BonReceptionController::class, 'show'])->name('bon-reception.show');
    Route::get('/{id}/export-pdf', [BonReceptionController::class, 'exportPdf'])->name('bon-reception.exportPdf');
    Route::post('/{id}/recevoir', [BonReceptionController::class, 'recevoir'])->name('bon-reception.recevoir');
    Route::post('/{id}/valider', [BonReceptionController::class, 'valider'])->name('bon-reception.valider');
    Route::post('/{id}/annuler', [BonReceptionController::class, 'annuler'])->name('bon-reception.annuler');
    Route::delete('/{id}', [BonReceptionController::class, 'destroy'])->name('bon-reception.destroy');
});

// Routes Mouvement de Stock
Route::prefix('mvt-stock')->group(function () {
    Route::get('/list', [MvtStockController::class, 'list'])->name('mvt-stock.list');
    Route::get('/create', [MvtStockController::class, 'create'])->name('mvt-stock.create');
    Route::get('/api/prix-actuel', [MvtStockController::class, 'getPrixActuel'])->name('mvt-stock.api.prix-actuel');
    Route::post('/', [MvtStockController::class, 'store'])->name('mvt-stock.store');
    Route::get('/{id}', [MvtStockController::class, 'show'])->name('mvt-stock.show');
    Route::get('/{id}/export-pdf', [MvtStockController::class, 'exportPdf'])->name('mvt-stock.exportPdf');
    Route::delete('/{id}', [MvtStockController::class, 'destroy'])->name('mvt-stock.destroy');
    Route::delete('/fille/{id}', [MvtStockController::class, 'destroyFille'])->name('mvt-stock.destroyFille');
});

// Détails des mouvements (articles enfants)
Route::get('/stock/details', [MvtStockController::class, 'details'])->name('stock.details');

// Alias pour l'état/etat des stocks (pointe vers la liste des mouvements)
Route::get('/stock/list', [MvtStockController::class, 'list'])->name('stock.list');

// État du stock par magasin
Route::get('/stock/etat', [MvtStockController::class, 'etat'])->name('stock.etat');

// Routes Magasin
Route::prefix('magasin')->group(function () {
    // Routes spécifiques AVANT les paramètres
    Route::get('/carte', [MagasinController::class, 'carte'])->name('magasin.carte');
    Route::get('/api/magasins', [MagasinController::class, 'getMagasins'])->name('magasin.magasins');
    Route::get('/api/entites-by-groupe', [MagasinController::class, 'getEntitesByGroupe'])->name('magasin.entitesByGroupe');
    Route::get('/api/sites-by-entite', [MagasinController::class, 'getSitesByEntite'])->name('magasin.sitesByEntite');
    Route::get('/api/magasins-by-site', [MagasinController::class, 'getMagasinsBySite'])->name('magasin.magasinsBySite');
    Route::get('/list', [MagasinController::class, 'list'])->name('magasin.list');
    Route::get('/create', [MagasinController::class, 'create'])->name('magasin.create');
    Route::post('/', [MagasinController::class, 'store'])->name('magasin.store');
    
    // Routes avec paramètres EN DERNIER
    Route::get('/{id}', [MagasinController::class, 'show'])->name('magasin.show');
    Route::get('/{id}/edit', [MagasinController::class, 'edit'])->name('magasin.edit');
    Route::put('/{id}', [MagasinController::class, 'update'])->name('magasin.update');
    Route::delete('/{id}', [MagasinController::class, 'destroy'])->name('magasin.destroy');
});
