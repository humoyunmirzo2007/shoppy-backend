<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramBot
{
    private static function sendMessage(string $message, string $parseMode = 'HTML')
    {
        try {
            $token = config('services.telegram.telegram_bug_log_bot_token');
            $chatId = config('services.telegram.telegram_bug_log_group_chat_id');

            if (! $token || ! $chatId) {
                Log::warning('Telegram bot token yoki chat_id sozlanmagan');

                return null;
            }

            $url = "https://api.telegram.org/bot{$token}/sendMessage";
            $data = [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => $parseMode,
                'disable_web_page_preview' => true,
            ];

            $response = Http::timeout(10)->post($url, $data);
            $responseData = $response->json();

            return $responseData;
        } catch (\Throwable $th) {
            Log::error('TelegramBot sendMessage xatosi: '.$th->getMessage());

            return null;
        }
    }

    public static function sendError(Request $request, \Exception $e)
    {
        try {
            // Vaqt ma'lumotlari
            $currentTime = now()->format('Y-m-d H:i:s');
            $timezone = config('app.timezone', 'UTC');

            // Request ma'lumotlari
            $requestMethod = $request->method();
            $requestUrl = $request->fullUrl();
            $requestIp = $request->ip();
            $requestUserAgent = $request->userAgent();
            $requestHeaders = json_encode($request->headers->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $requestBody = json_encode($request->all(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $requestRoute = $request->route() ? $request->route()->getName() : 'N/A';
            $requestController = $request->route() && $request->route()->getActionName() ? $request->route()->getActionName() : 'N/A';

            // Exception ma'lumotlari
            $exceptionMessage = $e->getMessage();
            $exceptionFile = $e->getFile();
            $exceptionLine = $e->getLine();
            $exceptionCode = $e->getCode();
            $exceptionClass = get_class($e);
            $exceptionTrace = $e->getTraceAsString();

            // Fayl nomini to'liq yo'ldan ajratib olish
            $filePath = str_replace(base_path(), '', $exceptionFile);

            // Xabar formatlash
            $message = '';

            $message .= "📍 <b>Xato Ma'lumotlari</b>\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            $message .= "🔴 <b>Xato:</b> <code>{$exceptionClass}</code>\n";
            $message .= "💬 <b>Xabar:</b> {$exceptionMessage}\n";
            $message .= "📁 <b>Fayl:</b> <code>{$filePath}</code>\n";
            $message .= "📍 <b>Qator:</b> <code>{$exceptionLine}</code>\n";
            $message .= "🔢 <b>Kod:</b> <code>{$exceptionCode}</code>\n\n";

            $message .= "🌐 <b>Request Ma'lumotlari</b>\n";
            $message .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            $message .= "🔹 <b>Method:</b> <code>{$requestMethod}</code>\n";
            $message .= "🔹 <b>URL:</b> <code>{$requestUrl}</code>\n";
            $message .= "🔹 <b>Route:</b> <code>{$requestRoute}</code>\n";
            $message .= "🔹 <b>Controller:</b> <code>{$requestController}</code>\n";
            $message .= "🔹 <b>IP:</b> <code>{$requestIp}</code>\n";
            $message .= "🔹 <b>User-Agent:</b> <code>{$requestUserAgent}</code>\n\n";

            $message .= "📦 <b>Request Body:</b>\n";
            $message .= "<pre>{$requestBody}</pre>\n\n";

            return self::sendMessage($message);
        } catch (\Exception $exception) {
            Log::error('TelegramBot sendError xatosi: '.$exception->getMessage());

            return null;
        }
    }
}
