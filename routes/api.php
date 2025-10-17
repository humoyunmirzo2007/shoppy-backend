<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Bot moduli route'lari
require __DIR__.'/../app/Modules/Bot/routes.php';

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Health check endpoint for Docker
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'services' => [
            'database' => 'connected', // Bu yerda database connection tekshirilishi mumkin
        ],
    ]);
});
