<?php

use App\Modules\Cashbox\Controllers\MoneyInputController;
use App\Modules\Cashbox\Controllers\MoneyOutputController;
use App\Modules\Cashbox\Controllers\TransferController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'money-inputs', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [MoneyInputController::class, 'index']);
    Route::get('/{id}', [MoneyInputController::class, 'show']);
    Route::post('/', [MoneyInputController::class, 'store']);
    Route::put('/{id}', [MoneyInputController::class, 'update']);
    Route::delete('/{id}', [MoneyInputController::class, 'destroy']);
});

Route::group(['prefix' => 'money-outputs', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [MoneyOutputController::class, 'index']);
    Route::get('/{id}', [MoneyOutputController::class, 'show']);
    Route::post('/', [MoneyOutputController::class, 'store']);
    Route::put('/{id}', [MoneyOutputController::class, 'update']);
    Route::delete('/{id}', [MoneyOutputController::class, 'destroy']);
});

Route::group(['prefix' => 'transfers', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [TransferController::class, 'index']);
    Route::get('/{id}', [TransferController::class, 'show']);
    Route::post('/', [TransferController::class, 'store']);
    Route::delete('/{id}', [TransferController::class, 'destroy']);
});
