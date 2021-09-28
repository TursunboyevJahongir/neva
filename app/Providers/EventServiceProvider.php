<?php

namespace App\Providers;

use App\Models\Basket;
use App\Models\Shop;
use App\Observers\Basket\BasketObserver;
use App\Observers\Shop\ShopObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
      //  Shop::observe(ShopObserver::class);
       /// Category::observe(CategoryObserver::class);
       // Product::observe(ProductObserver::class);
       // ProductVariation::observe(ProductVariationObserver::class);
      //  News::observe(NewsObserver::class);
       // Banner::observe(BannerObserver::class);
        Basket::observe(BasketObserver::class);
    }
}
