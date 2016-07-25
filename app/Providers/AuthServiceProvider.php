<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/**
 * Class AuthServiceProvider
 * @package App\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $gate->define('api-read', function ($user) {
            return $user->hasAbility('grant_api-read');
        });

        $gate->define('api-list', function ($user) {
            return $user->hasAbility('grant_api-list');
        });

        $gate->define('api-write:sensorreading', function ($user) {
            return $user->hasAbility('grant_api-write:sensorreading');
        });

        $gate->define('api-write:terrarium', function ($user) {
            return $user->hasAbility('grant_api-write:terrarium');
        });

        $gate->define('api-write:animal', function ($user) {
            return $user->hasAbility('grant_api-write:animal');
        });

        $gate->define('api-write:valve', function ($user) {
            return $user->hasAbility('grant_api-write:valve');
        });

        $gate->define('api-write:pump', function ($user) {
            return $user->hasAbility('grant_api-write:pump');
        });

        $gate->define('api-write:physical_sensor', function ($user) {
            return $user->hasAbility('grant_api-write:physical_sensor');
        });

        $gate->define('api-write:logical_sensor', function ($user) {
            return $user->hasAbility('grant_api-write:logical_sensor');
        });

        $gate->define('api-write:controlunit', function ($user) {
            return $user->hasAbility('grant_api-write:controlunit');
        });
    }
}