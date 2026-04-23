<?php

namespace App\Providers;

use App\Models\Location;
use App\Models\Product;
use App\Models\StockOpname;
use App\Policies\LocationPolicy;
use App\Policies\ProductPolicy;
use App\Policies\StockOpnamePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Policy mapping. Laravel 13 auto-discovery sudah cukup,
     * tapi kita register eksplisit agar jelas & tahan refactor namespace.
     *
     * @var array<class-string, class-string>
     */
    protected array $policies = [
        Location::class => LocationPolicy::class,
        Product::class => ProductPolicy::class,
        StockOpname::class => StockOpnamePolicy::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
