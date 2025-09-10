<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Cashbox\Controllers\PaymentController;
use App\Modules\Cashbox\Controllers\CostController;
use App\Modules\Cashbox\Controllers\TransferController;

Route::group(['prefix' => 'money-inputs', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [PaymentController::class, 'index']);
    Route::get('/{id}', [PaymentController::class, 'show']);
    Route::post('/', [PaymentController::class, 'store']);
    Route::put('/{id}', [PaymentController::class, 'update']);
    Route::delete('/{id}', [PaymentController::class, 'destroy']);
});

Route::group(['prefix' => 'money-outputs', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [CostController::class, 'index']);
    Route::get('/{id}', [CostController::class, 'show']);
    Route::post('/', [CostController::class, 'store']);
    Route::put('/{id}', [CostController::class, 'update']);
    Route::delete('/{id}', [CostController::class, 'destroy']);
});

Route::group(['prefix' => 'transfers', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [TransferController::class, 'index']);
    Route::get('/{id}', [TransferController::class, 'show']);
    Route::post('/', [TransferController::class, 'store']);
    Route::delete('/{id}', [TransferController::class, 'destroy']);
});
