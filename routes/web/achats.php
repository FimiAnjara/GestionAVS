<?php

use App\Http\Controllers\ProformaFournisseurController;
use App\Http\Controllers\BonCommandeController;
use App\Http\Controllers\FactureFournisseurController;
use Illuminate\Support\Facades\Route;

// Routes Proforma Fournisseur (Achats)
Route::prefix('proforma-fournisseur')->group(function () {
    Route::get('/list', [ProformaFournisseurController::class, 'list'])->name('proforma-fournisseur.list');
    Route::get('/create', [ProformaFournisseurController::class, 'create'])->name('proforma-fournisseur.create');
    Route::post('/', [ProformaFournisseurController::class, 'store'])->name('proforma-fournisseur.store');
    Route::get('/{id}', [ProformaFournisseurController::class, 'show'])->name('proforma-fournisseur.show');
    Route::get('/{id}/edit', [ProformaFournisseurController::class, 'edit'])->name('proforma-fournisseur.edit');
    Route::put('/{id}', [ProformaFournisseurController::class, 'update'])->name('proforma-fournisseur.update');
    Route::get('/{id}/export-pdf', [ProformaFournisseurController::class, 'exportPdf'])->name('proforma-fournisseur.exportPdf');
    Route::post('/{id}/etat', [ProformaFournisseurController::class, 'changerEtat'])->name('proforma-fournisseur.etat');
    Route::delete('/{id}', [ProformaFournisseurController::class, 'destroy'])->name('proforma-fournisseur.destroy');
});

// Routes Bon de Commande
Route::prefix('bon-commande')->group(function () {
    Route::get('/list', [BonCommandeController::class, 'list'])->name('bon-commande.list');
    Route::get('/create', [BonCommandeController::class, 'create'])->name('bon-commande.create');
    Route::get('/api/proforma/{id}', [BonCommandeController::class, 'getProformaData'])->name('bon-commande.api.proforma');
    Route::post('/', [BonCommandeController::class, 'store'])->name('bon-commande.store');
    Route::get('/{id}', [BonCommandeController::class, 'show'])->name('bon-commande.show');
    Route::get('/{id}/export-pdf', [BonCommandeController::class, 'exportPdf'])->name('bon-commande.exportPdf');
    Route::post('/{id}/etat', [BonCommandeController::class, 'changerEtat'])->name('bon-commande.etat');
    Route::delete('/{id}', [BonCommandeController::class, 'destroy'])->name('bon-commande.destroy');
});

// Routes Facture Fournisseur
Route::prefix('facture-fournisseur')->group(function () {
    Route::get('/list', [FactureFournisseurController::class, 'list'])->name('facture-fournisseur.list');
    Route::get('/create', [FactureFournisseurController::class, 'createFromBonCommande'])->name('facture-fournisseur.create');
    Route::get('/create/{id_bonCommande}', [FactureFournisseurController::class, 'createFromBonCommande'])->name('facture-fournisseur.createFromBonCommande');
    Route::post('/', [FactureFournisseurController::class, 'store'])->name('facture-fournisseur.store');
    Route::get('/{id}', [FactureFournisseurController::class, 'show'])->name('facture-fournisseur.show');
    Route::post('/{id}/etat', [FactureFournisseurController::class, 'changerEtat'])->name('facture-fournisseur.changerEtat');
    Route::get('/{id}/export-pdf', [FactureFournisseurController::class, 'exportPdf'])->name('facture-fournisseur.exportPdf');
    Route::delete('/{id}', [FactureFournisseurController::class, 'destroy'])->name('facture-fournisseur.destroy');
});
