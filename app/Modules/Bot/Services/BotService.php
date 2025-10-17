<?php

namespace App\Modules\Bot\Services;

use App\Helpers\TelegramBugNotifier;
use App\Modules\Information\Services\ClientService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BotService
{
    public function __construct(
        protected ClientService $clientService,
        protected TelegramBugNotifier $telegramNotifier
    ) {}

    /**
     * Bot start komandasi uchun handler
     */
    public function handleStartCommand(array $telegramData)
    {
        try {
            $chatId = (string) ($telegramData['message']['chat']['id'] ?? null);

            if (! $chatId) {
                return [
                    'status' => 'error',
                    'message' => 'Chat ID topilmadi',
                ];
            }

            // Telegram API ga xabar yuborish
            $telegramToken = config('services.telegram.shoppy_uz_bot_token');
            $telegramApiUrl = "https://api.telegram.org/bot{$telegramToken}/sendMessage";

            $firstName = $telegramData['message']['chat']['first_name'] ?? null;
            $response = Http::post($telegramApiUrl, [
                'chat_id' => $chatId,
                'text' => '🎉 <b>Assalomu alaykum!</b> '.$firstName.'! 👋'."\n\n".
                         '🛍️ <b>Shoppy.uz</b> botimizga xush kelibsiz!'."\n\n".
                         '📝 Ro\'yxatdan o\'tish uchun telefon raqamingizni yuboring.'."\n\n".
                         '👇 <b>Pastdagi tugma orqali telefon raqamingizni yuboring</b>',
                'parse_mode' => 'HTML',
                'reply_markup' => [
                    'keyboard' => [
                        [
                            [
                                'text' => '📱 Telefon raqamini yuborish',
                                'request_contact' => true,
                            ],
                        ],
                    ],
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true,
                ],
            ]);

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'message' => 'Salom xabari muvaffaqiyatli yuborildi',
                ];
            } else {
                Log::error('Telegram API xatoligi:', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'chat_id' => $chatId,
                ]);

                return [
                    'status' => 'error',
                    'message' => 'Telegram API ga xabar yuborishda xatolik: '.$response->body(),
                ];
            }

        } catch (\Throwable $e) {
            Log::error('Start komandasi xatoligi:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $telegramData,
            ]);

            return [
                'status' => 'error',
                'message' => 'Start komandasi bajarishda xatolik yuz berdi',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Telefon raqam qabul qilinganda handler
     */
    public function handleContactReceived(array $telegramData)
    {
        try {
            $chatId = (string) ($telegramData['message']['chat']['id'] ?? null);
            $contact = $telegramData['message']['contact'] ?? null;

            if (! $chatId || ! $contact) {
                return [
                    'status' => 'error',
                    'message' => 'Chat ID yoki contact ma\'lumotlari topilmadi',
                ];
            }

            $phoneNumber = $contact['phone_number'] ?? null;
            $firstName = $contact['first_name'] ?? null;
            $lastName = $contact['last_name'] ?? null;
            $username = $telegramData['message']['chat']['username'] ?? null;

            $clientData = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'username' => $username,
                'phone_number' => $phoneNumber,
                'chat_id' => $chatId,
                'is_active' => true,
            ];

            // Avval client mavjudligini tekshirish
            $existingClientResult = $this->clientService->getByChatId($chatId);

            if ($existingClientResult['status'] === 'success') {
                // Mavjud clientni yangilash
                $clientResult = $this->clientService->update($existingClientResult['data']->id, $clientData);
            } else {
                // Yangi client yaratish
                $clientResult = $this->clientService->store($clientData);
            }

            if ($clientResult['status'] !== 'success') {
                return [
                    'status' => 'error',
                    'message' => 'Client yaratishda xatolik: '.$clientResult['message'],
                ];
            }

            $client = $clientResult['data'];

            // Telegram API ga javob xabar yuborish
            $telegramToken = config('services.telegram.shoppy_uz_bot_token');
            $telegramApiUrl = "https://api.telegram.org/bot{$telegramToken}/sendMessage";

            // Xabar matnini mavjud clientga qarab belgilash
            $messageText = $existingClientResult['status'] === 'success'
                ? '🔄 <b>Ma\'lumotlar yangilandi!</b>'."\n\n".
                  '📱 <b>Telefon raqam:</b> '.$phoneNumber."\n".
                  '👤 <b>Ism:</b> '.$firstName."\n\n".
                  '✅ <b>Shoppy.uz</b> botimizda ma\'lumotlaringiz yangilandi!'."\n\n".
                  '🛒 Endi botimizdan to\'liq foydalanishingiz mumkin!'
                : '🎊 <b>Tabriklaymiz!</b> Ro\'yxatdan muvaffaqiyatli o\'tdingiz! ✅'."\n\n".
                  '📱 <b>Telefon raqam:</b> '.$phoneNumber."\n".
                  '👤 <b>Ism:</b> '.$firstName."\n\n".
                  '🛒 Endi <b>Shoppy.uz</b> botimizdan to\'liq foydalanishingiz mumkin!'."\n\n".
                  '🎁 <b>Maxsus chegirmalar va yangi mahsulotlar sizni kutmoqda!</b>';

            $response = Http::post($telegramApiUrl, [
                'chat_id' => $chatId,
                'text' => $messageText,
                'parse_mode' => 'HTML',
                'reply_markup' => [
                    'remove_keyboard' => true,
                ],
            ]);

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'message' => $existingClientResult['status'] === 'success'
                        ? 'Telefon raqam muvaffaqiyatli qabul qilindi va client yangilandi'
                        : 'Telefon raqam muvaffaqiyatli qabul qilindi va client yaratildi',
                    'phone_number' => $phoneNumber,
                    'first_name' => $firstName,
                    'client_id' => $client->id,
                    'is_updated' => $existingClientResult['status'] === 'success' ? true : false,
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Telegram API ga javob yuborishda xatolik',
                ];
            }

        } catch (\Throwable $e) {
            return [
                'status' => 'error',
                'message' => 'Telefon raqam qabul qilishda xatolik yuz berdi',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * /start komandasi bilan contact yuborilganda handler
     */
    public function handleStartWithContact(array $telegramData)
    {
        try {
            $chatId = (string) ($telegramData['message']['chat']['id'] ?? null);
            $contact = $telegramData['message']['contact'] ?? null;

            if (! $chatId || ! $contact) {
                return [
                    'status' => 'error',
                    'message' => 'Chat ID yoki contact ma\'lumotlari topilmadi',
                ];
            }

            $phoneNumber = $contact['phone_number'] ?? null;
            $firstName = $contact['first_name'] ?? null;
            $lastName = $contact['last_name'] ?? null;
            $username = $telegramData['message']['chat']['username'] ?? null;

            // Client ma'lumotlarini yangilash
            $clientData = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'username' => $username,
                'phone_number' => $phoneNumber,
                'chat_id' => $chatId,
                'is_active' => true,
            ];

            // Avval client mavjudligini tekshirish
            $existingClientResult = $this->clientService->getByChatId($chatId);

            if ($existingClientResult['status'] === 'success') {
                // Mavjud clientni yangilash
                $clientResult = $this->clientService->update($existingClientResult['data']->id, $clientData);
            } else {
                // Yangi client yaratish
                $clientResult = $this->clientService->store($clientData);
            }

            if ($clientResult['status'] !== 'success') {
                return [
                    'status' => 'error',
                    'message' => 'Client yangilashda xatolik: '.$clientResult['message'],
                ];
            }

            $client = $clientResult['data'];

            // Telegram API ga javob xabar yuborish
            $telegramToken = config('services.telegram.shoppy_uz_bot_token');
            $telegramApiUrl = "https://api.telegram.org/bot{$telegramToken}/sendMessage";

            $response = Http::post($telegramApiUrl, [
                'chat_id' => $chatId,
                'text' => '🔄 <b>Ma\'lumotlar yangilandi!</b>'."\n\n".
                         '📱 <b>Telefon raqam:</b> '.$phoneNumber."\n".
                         '👤 <b>Ism:</b> '.$firstName."\n\n".
                         '✅ <b>Shoppy.uz</b> botimizda ma\'lumotlaringiz yangilandi!'."\n\n".
                         '🛒 Endi botimizdan to\'liq foydalanishingiz mumkin!',
                'parse_mode' => 'HTML',
                'reply_markup' => [
                    'remove_keyboard' => true,
                ],
            ]);

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'message' => 'Client ma\'lumotlari muvaffaqiyatli yangilandi',
                    'phone_number' => $phoneNumber,
                    'first_name' => $firstName,
                    'client_id' => $client->id,
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Telegram API ga javob yuborishda xatolik',
                ];
            }

        } catch (\Throwable $e) {
            return [
                'status' => 'error',
                'message' => 'Start with contact bajarishda xatolik yuz berdi',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Telegram webhook uchun umumiy handler
     */
    public function handleWebhook(array $telegramData)
    {
        try {
            // Telegram ma'lumotlarini logga yozish
            Log::info('Telegram webhook ma\'lumotlari:', $telegramData);

            // Agar /start komandasi kelsa
            if (isset($telegramData['message']['text']) &&
                str_starts_with($telegramData['message']['text'], '/start')) {
                return $this->handleStartCommand($telegramData);
            }

            // Agar telefon raqam yuborilgan bo'lsa
            if (isset($telegramData['message']['contact'])) {
                return $this->handleContactReceived($telegramData);
            }

            // Agar /start komandasi va contact birga kelsa
            if (isset($telegramData['message']['text']) &&
                str_starts_with($telegramData['message']['text'], '/start') &&
                isset($telegramData['message']['contact'])) {
                return $this->handleStartWithContact($telegramData);
            }

            // Boshqa holatda faqat tezkor javob qaytarish
            return [
                'status' => 'success',
                'message' => 'Webhook muvaffaqiyatli qabul qilindi',
                'debug_data' => $telegramData, // Debug uchun ma'lumotlarni qaytarish
            ];

        } catch (\Throwable $e) {
            Log::error('Webhook xatoligi:', [
                'error' => $e->getMessage(),
                'data' => $telegramData,
            ]);

            return [
                'status' => 'error',
                'message' => 'Webhook bajarishda xatolik yuz berdi',
                'error' => $e->getMessage(),
            ];
        }
    }
}
