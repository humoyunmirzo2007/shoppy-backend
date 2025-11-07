<?php

namespace App\Modules\Trade;

use App\Modules\Trade\Interfaces\ClientCalculationInterface;
use App\Modules\Trade\Interfaces\TradeInterface;
use App\Modules\Trade\Interfaces\TradeProductInterface;
use App\Modules\Trade\Repositories\ClientCalculationRepository;
use App\Modules\Trade\Repositories\TradeProductRepository;
use App\Modules\Trade\Repositories\TradeRepository;
use Illuminate\Support\ServiceProvider;

class TradeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TradeInterface::class, TradeRepository::class);
        $this->app->singleton(TradeProductInterface::class, TradeProductRepository::class);
        $this->app->singleton(ClientCalculationInterface::class, ClientCalculationRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
