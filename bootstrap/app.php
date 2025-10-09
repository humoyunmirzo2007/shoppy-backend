<?php

use App\Http\Middleware\ReturnIfHasRequestMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            foreach (
                [
                    'Information',
                    'Warehouse',
                    'Trade',
                    'Cashbox',
                ] as $module
            ) {
                $routeFile = base_path("app/Modules/{$module}/routes.php");

                if (file_exists($routeFile)) {
                    Route::group(['prefix' => 'api'], function () use ($routeFile) {
                        require $routeFile;
                    });
                }
            }
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'reject_request' => ReturnIfHasRequestMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
