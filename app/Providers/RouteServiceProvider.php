<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapSetupRoutes();

        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapAuthRoutes();

        $this->mapTelegramRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace('App\\Http\\Controllers\\Web')
             ->group(base_path('routes/web.php'));
    }

    protected function mapApiRoutes()
    {
        Route::prefix('api/v1')
             ->middleware('auth:api', 'localization')
             ->namespace('App\\Http\\Controllers\\Api')
             ->group(base_path('routes/api.php'));
    }

    protected function mapAuthRoutes()
    {
        Route::prefix('auth')
            ->middleware('web')
            ->namespace('App\\Http\\Controllers')
            ->group(base_path('routes/auth.php'));
    }

    protected function mapSetupRoutes()
    {
        Route::prefix('api/v1/setup')
            ->middleware('localization')
            ->namespace('App\\Http\\Controllers\\Api')
            ->group(base_path('routes/setup.php'));
    }

    protected function mapTelegramRoutes()
    {
        Route::prefix('api/v1')
            ->namespace('App\\Http\\Controllers\\Api')
            ->group(base_path('routes/telegram.php'));
    }
}
