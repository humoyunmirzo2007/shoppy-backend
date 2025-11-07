<?php

namespace App\Modules\Information;

use App\Modules\Information\Interfaces\AttributeInterface;
use App\Modules\Information\Interfaces\AttributeValueInterface;
use App\Modules\Information\Interfaces\BrandInterface;
use App\Modules\Information\Interfaces\CategoryInterface;
use App\Modules\Information\Interfaces\ClientInterface;
use App\Modules\Information\Interfaces\CostTypeInterface;
use App\Modules\Information\Interfaces\OtherSourceInterface;
use App\Modules\Information\Interfaces\PaymentTypeInterface;
use App\Modules\Information\Interfaces\ProductInterface;
use App\Modules\Information\Interfaces\SupplierInterface;
use App\Modules\Information\Interfaces\UserInterface;
use App\Modules\Information\Repositories\AttributeRepository;
use App\Modules\Information\Repositories\AttributeValueRepository;
use App\Modules\Information\Repositories\BrandRepository;
use App\Modules\Information\Repositories\CategoryRepository;
use App\Modules\Information\Repositories\ClientRepository;
use App\Modules\Information\Repositories\CostTypeRepository;
use App\Modules\Information\Repositories\OtherSourceRepository;
use App\Modules\Information\Repositories\PaymentTypeRepository;
use App\Modules\Information\Repositories\ProductRepository;
use App\Modules\Information\Repositories\SupplierRepository;
use App\Modules\Information\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class InformationServiceProvider extends ServiceProvider
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
        $this->app->singleton(AttributeInterface::class, AttributeRepository::class);
        $this->app->singleton(AttributeValueInterface::class, AttributeValueRepository::class);
        $this->app->singleton(BrandInterface::class, BrandRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
