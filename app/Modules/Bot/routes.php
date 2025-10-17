<?php

use App\Modules\Bot\Controllers\BotController;
use Illuminate\Support\Facades\Route;

Route::prefix('bot')->group(function () {
    // Bot start komandasi uchun
    Route::post('/start', [BotController::class, 'handleStart']);

    // Telegram webhook uchun umumiy handler
    Route::post('/webhook', [BotController::class, 'handleWebhook']);

});
