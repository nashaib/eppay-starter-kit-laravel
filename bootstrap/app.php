<?php

// bootstrap/app.php - Update the existing file

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
        // Register route middleware aliases
        $middleware->alias([
            'seller.auth' => \App\Http\Middleware\SellerAuth::class,
            'admin.auth' => \App\Http\Middleware\AdminAuth::class,
            'buyer.auth' => \App\Http\Middleware\BuyerAuth::class,
        ]);
        
        // Web middleware group already includes necessary middleware
        // Just add any custom global middleware here if needed
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();