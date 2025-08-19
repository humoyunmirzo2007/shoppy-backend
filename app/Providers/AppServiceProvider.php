<?php

namespace App\Providers;

use App\Modules\Information\Interfaces\CategoryInterface;
use App\Modules\Information\Interfaces\CostTypeInterface;
use App\Modules\Information\Interfaces\ProductInterface;
use App\Modules\Information\Interfaces\SupplierInterface;
use App\Modules\Information\Interfaces\UserInterface;
use App\Modules\Information\Repositories\CategoryRepository;
use App\Modules\Information\Repositories\CostTypeRepository;
use App\Modules\Information\Repositories\ProductRepository;
use App\Modules\Information\Repositories\SupplierRepository;
use App\Modules\Information\Repositories\UserRepository;
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
        $this->app->singleton(CostTypeInterface::class, CostTypeRepository::class);
        $this->app->singleton(ProductInterface::class, ProductRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
