<?php

namespace App\Modules\Bot\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Bot\Services\BotService;
use Illuminate\Http\Request;

class BotController extends Controller
{
    public function __construct(
        protected BotService $botService
    ) {}

    /**
     * Bot start komandasi uchun handler
     */
    public function handleStart(Request $request)
    {
        try {
            $telegramData = $request->all();

            $result = $this->botService->handleStartCommand($telegramData);

            return response()->json($result);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bot start komandasi bajarishda xatolik yuz berdi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Telegram webhook uchun umumiy handler
     */
    public function handleWebhook(Request $request)
    {
        try {
            $telegramData = $request->all();

            $result = $this->botService->handleWebhook($telegramData);

            return response()->json($result);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Webhook bajarishda xatolik yuz berdi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
