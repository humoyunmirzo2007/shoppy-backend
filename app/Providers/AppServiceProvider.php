<?php

namespace App\Providers;

use App\Commands;
use App\Modules\Cashbox\Interfaces\MoneyInputInterface;
use App\Modules\Cashbox\Interfaces\MoneyOutputInterface;
use App\Modules\Cashbox\Interfaces\OtherCalculationInterface;
use App\Modules\Cashbox\Repositories\MoneyInputRepository;
use App\Modules\Cashbox\Repositories\MoneyOutputRepository;
use App\Modules\Cashbox\Repositories\OtherCalculationRepository;
use App\Modules\Information\Interfaces\CategoryInterface;
use App\Modules\Information\Interfaces\ClientInterface;
use App\Modules\Information\Interfaces\CostTypeInterface;
use App\Modules\Information\Interfaces\OtherSourceInterface;
use App\Modules\Information\Interfaces\PaymentTypeInterface;
use App\Modules\Information\Interfaces\ProductInterface;
use App\Modules\Information\Interfaces\SupplierInterface;
use App\Modules\Information\Interfaces\UserInterface;
use App\Modules\Information\Repositories\CategoryRepository;
use App\Modules\Information\Repositories\ClientRepository;
use App\Modules\Information\Repositories\CostTypeRepository;
use App\Modules\Information\Repositories\OtherSourceRepository;
use App\Modules\Information\Repositories\PaymentTypeRepository;
use App\Modules\Information\Repositories\ProductRepository;
use App\Modules\Information\Repositories\SupplierRepository;
use App\Modules\Information\Repositories\UserRepository;
use App\Modules\Trade\Interfaces\ClientCalculationInterface;
use App\Modules\Trade\Interfaces\TradeInterface;
use App\Modules\Trade\Interfaces\TradeProductInterface;
use App\Modules\Trade\Repositories\ClientCalculationRepository;
use App\Modules\Trade\Repositories\TradeProductRepository;
use App\Modules\Trade\Repositories\TradeRepository;
use App\Modules\Warehouse\Interfaces\InvoiceInterface;
use App\Modules\Warehouse\Interfaces\InvoiceProductInterface;
use App\Modules\Warehouse\Interfaces\SupplierCalculationInterface;
use App\Modules\Warehouse\Repositories\InvoiceProductRepository;
use App\Modules\Warehouse\Repositories\InvoiceRepository;
use App\Modules\Warehouse\Repositories\SupplierCalculationRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UserInterface::class, UserRepository::class);
        $this->app->singleton(CategoryInterface::class, CategoryRepository::class);
        $this->app->singleton(SupplierInterface::class, SupplierRepository::class);
        $this->app->singleton(ClientInterface::class, ClientRepository::class);
        $this->app->singleton(CostTypeInterface::class, CostTypeRepository::class);
        $this->app->singleton(ProductInterface::class, ProductRepository::class);
        $this->app->singleton(OtherSourceInterface::class, OtherSourceRepository::class);
        $this->app->singleton(PaymentTypeInterface::class, PaymentTypeRepository::class);

        $this->app->singleton(InvoiceInterface::class, InvoiceRepository::class);
        $this->app->singleton(InvoiceProductInterface::class, InvoiceProductRepository::class);
        $this->app->singleton(TradeInterface::class, TradeRepository::class);
        $this->app->singleton(TradeProductInterface::class, TradeProductRepository::class);
        $this->app->singleton(ClientCalculationInterface::class, ClientCalculationRepository::class);
        $this->app->singleton(SupplierCalculationInterface::class, SupplierCalculationRepository::class);
        $this->app->singleton(MoneyInputInterface::class, MoneyInputRepository::class);
        $this->app->singleton(MoneyOutputInterface::class, MoneyOutputRepository::class);
        $this->app->singleton(OtherCalculationInterface::class, OtherCalculationRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(Commands::register());
        }
    }
}
