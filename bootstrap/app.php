<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'jwt' => \App\Http\Middleware\JwtMiddleware::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
        
        // Exclure les routes API de la vérification CSRF
        $middleware->validateCsrfTokens(except: [
            'auth/*',
            'clients/*',
            'fournisseurs/*',
            'articles/*',
            'categories/*',
            'unites/*',
            'caisses/*',
            'mvt-caisse/*',
            'proformas-fournisseurs/*',
            'bons-commande/*',
            'factures-fournisseurs/*',
            'bons-reception/*',
            'mvt-stock/*',
            'magasins/*',
            'api/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
