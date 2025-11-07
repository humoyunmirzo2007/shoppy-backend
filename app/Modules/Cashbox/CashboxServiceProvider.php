<?php

namespace App\Modules\Cashbox;

use App\Modules\Cashbox\Interfaces\MoneyInputInterface;
use App\Modules\Cashbox\Interfaces\MoneyOutputInterface;
use App\Modules\Cashbox\Interfaces\OtherCalculationInterface;
use App\Modules\Cashbox\Repositories\MoneyInputRepository;
use App\Modules\Cashbox\Repositories\MoneyOutputRepository;
use App\Modules\Cashbox\Repositories\OtherCalculationRepository;
use Illuminate\Support\ServiceProvider;

class CashboxServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(MoneyInputInterface::class, MoneyInputRepository::class);
        $this->app->singleton(MoneyOutputInterface::class, MoneyOutputRepository::class);
        $this->app->singleton(OtherCalculationInterface::class, OtherCalculationRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
