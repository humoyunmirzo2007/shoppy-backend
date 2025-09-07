<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Cashbox\Controllers\CashboxController;

Route::group(['prefix' => 'cashboxes', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [CashboxController::class, 'index']);
    Route::get('/{id}', [CashboxController::class, 'show']);
    Route::post('/create', [CashboxController::class, 'store']);
    Route::put('/toggle-active/{id}', [CashboxController::class, 'toggleActive']);
});
