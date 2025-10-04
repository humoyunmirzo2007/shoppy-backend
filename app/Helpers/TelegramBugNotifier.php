<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class TelegramBugNotifier
{
    public function sendMessage($message)
    {
        $token = config('services.telegram.bot_token');
        $chat_id = config('services.telegram.chat_id');

        if (!$token || !$chat_id) {
            Log::error('Telegram bot token yoki chat ID o\'rnatilmagan.');
            return false;
        }

        $url = "https://api.telegram.org/bot{$token}/sendMessage";

        if (strlen($message) > 4096) {
            $message = substr($message, 0, 4093) . '...';
        }

        try {
            $response = Http::post($url, [
                'chat_id' => $chat_id,
                'text' => $message,
                'parse_mode' => 'HTML',
                'disable_web_page_preview' => true,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Telegram xabar yuborishda xatolik: ' . $e->getMessage());
            return false;
        }
    }

    public function sendError(Throwable $e, $request = null)
    {
        $message = "🚨 <b>Xatolik yuz berdi!</b>\n\n" .
            "📝 <b>Xatolik:</b> " . $e->getMessage() . "\n" .
            "📁 <b>Fayl:</b> " . basename($e->getFile()) . "\n" .
            "📍 <b>Qator:</b> " . $e->getLine() . "\n" .
            "⏰ <b>Vaqt:</b> " . now()->format('Y-m-d H:i:s') . "\n\n";

        // Request ma'lumotlarini qo'shish
        if ($request) {
            $message .= $this->formatRequestData($request) . "\n";
        }

        return $this->sendMessage($message);
    }

    /**
     * Request ma'lumotlarini formatlash
     */
    private function formatRequestData($request)
    {
        $data = "📥 <b>Request ma'lumotlari:</b>\n";

        if (is_object($request)) {
            // Laravel Request obyekti
            if (method_exists($request, 'method')) {
                $data .= "🔗 <b>Method:</b> " . $request->method() . "\n";
            }
            if (method_exists($request, 'url')) {
                $data .= "🌐 <b>URL:</b> " . $request->url() . "\n";
            }
            if (method_exists($request, 'ip')) {
                $data .= "📍 <b>IP:</b> " . $request->ip() . "\n";
            }
            if (method_exists($request, 'userAgent')) {
                $data .= "🖥️ <b>User Agent:</b> " . substr($request->userAgent(), 0, 100) . "\n";
            }
            if (method_exists($request, 'all')) {
                $allData = $request->all();
                if (!empty($allData)) {
                    $jsonData = $this->formatJsonData($allData);
                    $data .= "📋 <b>Request:</b>\n<pre>" . $jsonData . "</pre>\n";
                }
            }
        } elseif (is_array($request)) {
            // Array format
            $jsonData = $this->formatJsonData($request);
            $data .= "📋 <b>Request Data:</b>\n<pre>" . $jsonData . "</pre>\n";
        } else {
            // String format
            $data .= "📋 <b>Request:</b> " . substr($request, 0, 200) . "\n";
        }

        return $data;
    }

    /**
     * JSON ma'lumotlarini chiroyli formatlash
     */
    private function formatJsonData($data)
    {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        // Agar JSON juda uzun bo'lsa, qisqartirish
        if (strlen($json) > 1000) {
            $json = substr($json, 0, 997) . '...';
        }

        return $json;
    }
}
