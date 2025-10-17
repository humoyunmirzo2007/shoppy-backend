<?php

namespace App\Modules\Bot\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class RemoveWebhookCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:remove-webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Telegram bot webhook ni o\'chirish';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $botToken = config('services.telegram.shoppy_uz_bot_token');

        if (! $botToken) {
            $this->error('❌ Telegram bot token topilmadi! .env faylida SHOPPY_UZ_BOT_TOKEN ni o\'rnating.');

            return 1;
        }

        $this->info('🔄 Webhook o\'chirilmoqda...');

        try {
            $response = Http::post("https://api.telegram.org/bot{$botToken}/deleteWebhook", [
                'drop_pending_updates' => true,
            ]);

            if ($response->successful()) {
                $result = $response->json();

                if ($result['ok']) {
                    $this->info('✅ Webhook muvaffaqiyatli o\'chirildi!');
                } else {
                    $this->error('❌ Webhook o\'chirishda xatolik: '.($result['description'] ?? 'Noma\'lum xatolik'));

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
}
