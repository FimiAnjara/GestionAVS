<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\UniteController;
use App\Http\Controllers\CaisseController;
use App\Http\Controllers\MvtCaisseController;
use App\Http\Controllers\ProformaFournisseurController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('dashboard');
})->name('home');

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
    Route::get('/{id}/export-pdf', [ProformaFournisseurController::class, 'exportPdf'])->name('proforma-fournisseur.exportPdf');
    Route::post('/{id}/etat', [ProformaFournisseurController::class, 'changerEtat'])->name('proforma-fournisseur.etat');
    Route::delete('/{id}', [ProformaFournisseurController::class, 'destroy'])->name('proforma-fournisseur.destroy');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});
