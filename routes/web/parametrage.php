<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\UniteController;
use Illuminate\Support\Facades\Route;

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
