<?php

namespace App\Providers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use App\Models\Color;
use App\Models\Country;
use App\Models\Favourite;
use App\Models\Item;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Review;
use App\Models\Setting;
use App\Models\ShippingMethod;
use App\Models\Size;
use App\Models\Store;
use App\Models\Vat;
use App\Policies\BrandPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\CityPolicy;
use App\Policies\ColorPolicy;
use App\Policies\CountryPolicy;
use App\Policies\FavouritePolicy;
use App\Policies\ItemPolicy;
use App\Policies\OrderPolicy;
use App\Policies\PaymentMethodPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\SettingPolicy;
use App\Policies\ShippingMethodPolicy;
use App\Policies\SizePolicy;
use App\Policies\StorePolicy;
use App\Policies\VatPolicy;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = array(
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Vat::class            => VatPolicy::class,
        Item::class           => ItemPolicy::class,
        Size::class           => SizePolicy::class,
        Color::class          => ColorPolicy::class,
        Brand::class          => BrandPolicy::class,
        Store::class          => StorePolicy::class,
        Order::class          => OrderPolicy::class,
        Review::class         => ReviewPolicy::class,
        Setting::class        => SettingPolicy::class,
        Category::class       => CategoryPolicy::class,
        Favourite::class      => FavouritePolicy::class,
        PaymentMethod::class  => PaymentMethodPolicy::class,
        ShippingMethod::class => ShippingMethodPolicy::class,
    );

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (!$this->app->routesAreCached()) {
            Passport::routes();
        }
    }
}
