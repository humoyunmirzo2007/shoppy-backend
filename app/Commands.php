<?php

namespace App;

use App\Modules\Bot\Commands\RemoveWebhookCommand;
use App\Modules\Bot\Commands\SetWebhookCommand;

class Commands
{
    /**
     * Register all custom commands
     */
    public static function register(): array
    {
        return [
            SetWebhookCommand::class,
            RemoveWebhookCommand::class,
        ];
    }
}
