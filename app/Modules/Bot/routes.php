<?php

use App\Modules\Bot\Controllers\BotController;
use Illuminate\Support\Facades\Route;

Route::prefix('bot')->group(function () {
    Route::post('/start', [BotController::class, 'handleStart']);

    Route::post('/webhook', [BotController::class, 'handleWebhook']);

});
