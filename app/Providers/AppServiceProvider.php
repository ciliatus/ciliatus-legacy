<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Sensorreading;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Sensorreading::observe(SensorreadingObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
