<?php

namespace App\Modules\Warehouse;

use App\Modules\Warehouse\Interfaces\InvoiceInterface;
use App\Modules\Warehouse\Interfaces\InvoiceProductInterface;
use App\Modules\Warehouse\Interfaces\SupplierCalculationInterface;
use App\Modules\Warehouse\Repositories\InvoiceProductRepository;
use App\Modules\Warehouse\Repositories\InvoiceRepository;
use App\Modules\Warehouse\Repositories\SupplierCalculationRepository;
use Illuminate\Support\ServiceProvider;

class WarehouseServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(InvoiceInterface::class, InvoiceRepository::class);
        $this->app->singleton(InvoiceProductInterface::class, InvoiceProductRepository::class);
        $this->app->singleton(SupplierCalculationInterface::class, SupplierCalculationRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
