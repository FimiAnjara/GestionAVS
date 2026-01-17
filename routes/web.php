<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\FournisseurController;
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

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});
