<?php

use App\Modules\Trade\Controllers\ClientCalculationController;
use App\Modules\Trade\Controllers\TradeController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'trades', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/get-trades', [TradeController::class, 'getTrades']);
    Route::get('/get-return-products', [TradeController::class, 'getReturnProducts']);
    Route::get('/get-by-id/{id}', [TradeController::class, 'getByIdWithProducts']);
    Route::post('/create', [TradeController::class, 'store']);
    Route::put('/update/{id}', [TradeController::class, 'update']);
    Route::delete('/delete/{id}', [TradeController::class, 'delete']);
});

Route::group(['prefix' => 'client-calculations', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/get-by-client/{clientId}', [ClientCalculationController::class, 'getByClientId']);
});
