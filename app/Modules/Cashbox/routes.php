<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Cashbox\Controllers\PaymentController;
use App\Modules\Cashbox\Controllers\CostController;

Route::group(['prefix' => 'payments', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [PaymentController::class, 'index']);
    Route::get('/{id}', [PaymentController::class, 'show']);
    Route::post('/', [PaymentController::class, 'store']);
    Route::put('/{id}', [PaymentController::class, 'update']);
    Route::delete('/{id}', [PaymentController::class, 'destroy']);
});

Route::group(['prefix' => 'costs', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [CostController::class, 'index']);
    Route::get('/{id}', [CostController::class, 'show']);
    Route::post('/', [CostController::class, 'store']);
    Route::put('/{id}', [CostController::class, 'update']);
    Route::delete('/{id}', [CostController::class, 'destroy']);
});
