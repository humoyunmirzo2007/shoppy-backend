<?php

namespace App\Modules\Information\Services;

use App\Helpers\TelegramBot;
use Illuminate\Support\Facades\Http;

class TranslateService
{
    /**
     * Matnni tarjima qilish
     */
    public function translate(string $text, string $source = 'uz', string $target = 'ru'): array
    {
        try {
            $langpair = $source.'|'.$target;

            $response = Http::timeout(30)->get('https://api.mymemory.translated.net/get', [
                'q' => $text,
                'langpair' => $langpair,
            ]);

            if (! $response->successful()) {
                return [
                    'success' => false,
                    'message' => 'Tarjima qilishda xatolik yuz berdi',
                ];
            }

            $translatedText = $response->json('responseData.translatedText');

            if (! $translatedText) {
                return [
                    'success' => false,
                    'message' => 'Tarjima natijasi olinmadi',
                ];
            }

            return [
                'success' => true,
                'message' => 'Matn muvaffaqiyatli tarjima qilindi',
                'data' => [
                    'original' => $text,
                    'translated' => $translatedText,
                    'source' => $source,
                    'target' => $target,
                ],
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Tarjima qilishda xatolik yuz berdi',
            ];
        }
    }

    /**
     * Bir nechta matnlarni tarjima qilish
     */
    public function translateBatch(array $texts, string $source = 'uz', string $target = 'ru'): array
    {
        try {
            $results = [];

            foreach ($texts as $text) {
                $result = $this->translate($text, $source, $target);
                $results[] = $result['success'] ? $result['data'] : null;
            }

            return [
                'success' => true,
                'message' => 'Matnlar muvaffaqiyatli tarjima qilindi',
                'data' => $results,
            ];
        } catch (\Exception $e) {
            TelegramBot::sendError(request(), $e);

            return [
                'success' => false,
                'message' => 'Matnlarni tarjima qilishda xatolik yuz berdi',
            ];
        }
    }
}
