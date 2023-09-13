<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use App\Events\SubscriptionExpiration;
use Illuminate\Support\ServiceProvider;
use App\Listeners\CheckSubscriptionExpiration;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(SubscriptionExpiration::class, CheckSubscriptionExpiration::class);

        $activePlans = \App\Models\ActivePlans::all();

        foreach ($activePlans as $activePlan) {
            event(new SubscriptionExpiration($activePlan));
        }
    }
}