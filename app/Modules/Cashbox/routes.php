<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Cashbox\Controllers\CashboxController;
use App\Modules\Cashbox\Controllers\PaymentController;

Route::group(['prefix' => 'cashboxes', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [CashboxController::class, 'index']);
    Route::get('/{id}', [CashboxController::class, 'show']);
    Route::post('/create', [CashboxController::class, 'store']);
    Route::put('/toggle-active/{id}', [CashboxController::class, 'toggleActive']);
});

Route::group(['prefix' => 'payments', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [PaymentController::class, 'index']);
    Route::get('/{id}', [PaymentController::class, 'show']);
    Route::post('/', [PaymentController::class, 'store']);
    Route::put('/{id}', [PaymentController::class, 'update']);
    Route::delete('/{id}', [PaymentController::class, 'destroy']);
});
