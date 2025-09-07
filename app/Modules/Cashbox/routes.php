<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Cashbox\Controllers\PaymentController;

Route::group(['prefix' => 'payments', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [PaymentController::class, 'index']);
    Route::get('/{id}', [PaymentController::class, 'show']);
    Route::post('/', [PaymentController::class, 'store']);
    Route::put('/{id}', [PaymentController::class, 'update']);
    Route::delete('/{id}', [PaymentController::class, 'destroy']);
});
