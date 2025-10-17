<?php

namespace App\Modules\Bot\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SetWebhookCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:set-webhook {url?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Telegram bot uchun webhook o\'rnatish';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $webhookUrl = $this->argument('url') ?? config('app.url').'/api/bot/webhook';
        $botToken = config('services.telegram.shoppy_uz_bot_token');

        if (! $botToken) {
            $this->error('❌ Telegram bot token topilmadi! .env faylida SHOPPY_UZ_BOT_TOKEN ni o\'rnating.');

            return 1;
        }

        $this->info('🔄 Webhook o\'rnatilmoqda...');
        $this->info('📍 Webhook URL: '.$webhookUrl);

        try {
            $response = Http::post("https://api.telegram.org/bot{$botToken}/setWebhook", [
                'url' => $webhookUrl,
                'allowed_updates' => ['message', 'callback_query'],
                'drop_pending_updates' => true,
            ]);

            if ($response->successful()) {
                $result = $response->json();

                if ($result['ok']) {
                    $this->info('✅ Webhook muvaffaqiyatli o\'rnatildi!');
                    $this->info('📊 Ma\'lumot: '.($result['description'] ?? 'Webhook faol'));

                    // Webhook ma'lumotlarini tekshirish
                    $this->checkWebhookInfo();
                } else {
                    $this->error('❌ Webhook o\'rnatishda xatolik: '.($result['description'] ?? 'Noma\'lum xatolik'));

                    return 1;
                }
            } else {
                $this->error('❌ Telegram API ga bog\'lanishda xatolik');

                return 1;
            }
        } catch (\Exception $e) {
            $this->error('❌ Xatolik yuz berdi: '.$e->getMessage());

            return 1;
        }

        return 0;
    }

    /**
     * Webhook ma'lumotlarini tekshirish
     */
    private function checkWebhookInfo()
    {
        $botToken = config('services.telegram.shoppy_uz_bot_token');

        try {
            $response = Http::get("https://api.telegram.org/bot{$botToken}/getWebhookInfo");

            if ($response->successful()) {
                $result = $response->json();

                if ($result['ok']) {
                    $webhookInfo = $result['result'];

                    $this->info('📋 Webhook ma\'lumotlari:');
                    $this->info('   URL: '.($webhookInfo['url'] ?? 'O\'rnatilmagan'));
                    $this->info('   Pending updates: '.($webhookInfo['pending_update_count'] ?? 0));
                    $this->info('   Last error date: '.($webhookInfo['last_error_date'] ?? 'Xatolik yo\'q'));
                    $this->info('   Last error message: '.($webhookInfo['last_error_message'] ?? 'Xatolik yo\'q'));
                }
            }
        } catch (\Exception $e) {
            $this->warn('⚠️ Webhook ma\'lumotlarini olishda xatolik: '.$e->getMessage());
        }
    }
}
