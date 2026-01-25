<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\UniteController;
use App\Http\Controllers\CaisseController;
use App\Http\Controllers\MvtCaisseController;
use App\Http\Controllers\ProformaFournisseurController;
use App\Http\Controllers\BonCommandeController;
use App\Http\Controllers\FactureFournisseurController;
use App\Http\Controllers\BonReceptionController;
use App\Http\Controllers\MvtStockController;
use App\Http\Controllers\MagasinController;
use App\Http\Controllers\GroupeController;
use App\Http\Controllers\EntiteController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('dashboard.dashboard');
})->name('home');

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

// Routes Clients
Route::prefix('clients')->group(function () {
    Route::get('/list', [ClientController::class, 'index'])->name('clients.list');
    Route::get('/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('/', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/{id}', [ClientController::class, 'show'])->name('clients.show');
    Route::get('/{id}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/{id}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/{id}', [ClientController::class, 'destroy'])->name('clients.destroy');
    Route::get('/search/query', [ClientController::class, 'search'])->name('clients.search');
});

// Routes Fournisseurs
Route::prefix('fournisseurs')->group(function () {
    Route::get('/list', [FournisseurController::class, 'index'])->name('fournisseurs.list');
    Route::get('/create', [FournisseurController::class, 'create'])->name('fournisseurs.create');
    Route::post('/', [FournisseurController::class, 'store'])->name('fournisseurs.store');
    Route::get('/{id}', [FournisseurController::class, 'show'])->name('fournisseurs.show');
    Route::get('/{id}/edit', [FournisseurController::class, 'edit'])->name('fournisseurs.edit');
    Route::put('/{id}', [FournisseurController::class, 'update'])->name('fournisseurs.update');
    Route::delete('/{id}', [FournisseurController::class, 'destroy'])->name('fournisseurs.destroy');
    Route::get('/search/query', [FournisseurController::class, 'search'])->name('fournisseurs.search');
});

// Routes Articles
Route::prefix('articles')->group(function () {
    Route::get('/list', [ArticleController::class, 'index'])->name('articles.list');
    Route::get('/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/{id}', [ArticleController::class, 'show'])->name('articles.show');
    Route::get('/{id}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/{id}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/{id}', [ArticleController::class, 'destroy'])->name('articles.destroy');
    Route::get('/search/query', [ArticleController::class, 'search'])->name('articles.search');
});

// Routes Catégories
Route::prefix('categories')->group(function () {
    Route::get('/list', [CategorieController::class, 'index'])->name('categories.list');
    Route::get('/create', [CategorieController::class, 'create'])->name('categories.create');
    Route::post('/', [CategorieController::class, 'store'])->name('categories.store');
    Route::get('/{id}', [CategorieController::class, 'show'])->name('categories.show');
    Route::get('/{id}/edit', [CategorieController::class, 'edit'])->name('categories.edit');
    Route::put('/{id}', [CategorieController::class, 'update'])->name('categories.update');
    Route::delete('/{id}', [CategorieController::class, 'destroy'])->name('categories.destroy');
    Route::get('/search/query', [CategorieController::class, 'search'])->name('categories.search');
});

// Routes Unités
Route::prefix('unites')->group(function () {
    Route::get('/list', [UniteController::class, 'index'])->name('unites.list');
    Route::get('/create', [UniteController::class, 'create'])->name('unites.create');
    Route::post('/', [UniteController::class, 'store'])->name('unites.store');
    Route::get('/{id}', [UniteController::class, 'show'])->name('unites.show');
    Route::get('/{id}/edit', [UniteController::class, 'edit'])->name('unites.edit');
    Route::put('/{id}', [UniteController::class, 'update'])->name('unites.update');
    Route::delete('/{id}', [UniteController::class, 'destroy'])->name('unites.destroy');
    Route::get('/search/query', [UniteController::class, 'search'])->name('unites.search');
});

// Routes Types d'Évaluation de Stock
Route::prefix('type-evaluation-stock')->group(function () {
    Route::get('/list', [\App\Http\Controllers\TypeEvaluationStockController::class, 'index'])->name('type-evaluation-stock.list');
    Route::get('/create', [\App\Http\Controllers\TypeEvaluationStockController::class, 'create'])->name('type-evaluation-stock.create');
    Route::post('/', [\App\Http\Controllers\TypeEvaluationStockController::class, 'store'])->name('type-evaluation-stock.store');
    Route::get('/{id}', [\App\Http\Controllers\TypeEvaluationStockController::class, 'show'])->name('type-evaluation-stock.show');
    Route::get('/{id}/edit', [\App\Http\Controllers\TypeEvaluationStockController::class, 'edit'])->name('type-evaluation-stock.edit');
    Route::put('/{id}', [\App\Http\Controllers\TypeEvaluationStockController::class, 'update'])->name('type-evaluation-stock.update');
    Route::delete('/{id}', [\App\Http\Controllers\TypeEvaluationStockController::class, 'destroy'])->name('type-evaluation-stock.destroy');
});

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
    Route::post('/', [MvtStockController::class, 'store'])->name('mvt-stock.store');
    Route::get('/{id}', [MvtStockController::class, 'show'])->name('mvt-stock.show');
    Route::get('/{id}/export-pdf', [MvtStockController::class, 'exportPdf'])->name('mvt-stock.exportPdf');
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

