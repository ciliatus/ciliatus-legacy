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

        $gate->define('admin', function ($user) {
            return $user->hasAbility('grant_admin');
        });

        $gate->define('api-read', function ($user) {
            return $user->hasAbility('grant_api-read');
        });

        $gate->define('api-read_admin', function ($user) {
            return $user->hasAbility('grant_api-read_admin');
        });

        $gate->define('api-list', function ($user) {
            return $user->hasAbility('grant_api-list');
        });

        $gate->define('api-list:raw', function ($user) {
            return $user->hasAbility('grant_api-list:raw');
        });

        $gate->define('api-list_admin', function ($user) {
            return $user->hasAbility('grant_api-list_admin');
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

        $gate->define('api-write:animal_feeding', function ($user) {
            return $user->hasAbility('grant_api-write:animal_feeding');
        });

        $gate->define('api-write:animal_feeding_schedule', function ($user) {
            return $user->hasAbility('grant_api-write:animal_feeding_schedule');
        });

        $gate->define('api-write:animal_weighing', function ($user) {
            return $user->hasAbility('grant_api-write:animal_weighing');
        });

        $gate->define('api-write:animal_weighing_schedule', function ($user) {
            return $user->hasAbility('grant_api-write:animal_weighing_schedule');
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

        $gate->define('api-write:file', function ($user) {
            return $user->hasAbility('grant_api-write:file');
        });

        $gate->define('api-write:file_propertiy', function ($user) {
            return $user->hasAbility('grant_api-write:file_propertiy');
        });

        $gate->define('api-write:logical_sensor_threshold', function ($user) {
            return $user->hasAbility('grant_api-write:logical_sensor_threshold');
        });

        $gate->define('api-write:user_self', function ($user) {
            return $user->hasAbility('grant_api-write:user_self');
        });

        $gate->define('api-write:user_all', function ($user) {
            return $user->hasAbility('grant_api-write:user_all');
        });

        $gate->define('api-write:user_ability', function ($user) {
            return $user->hasAbility('grant_api-write:user_ability');
        });

        $gate->define('api-write:user_telegram', function ($user) {
            return $user->hasAbility('grant_api-write:user_telegram');
        });

        $gate->define('api-write:action', function ($user) {
            return $user->hasAbility('grant_api-write:action');
        });

        $gate->define('api-write:action_sequence', function ($user) {
            return $user->hasAbility('grant_api-write:action_sequence');
        });

        $gate->define('api-write:action_sequence_schedule', function ($user) {
            return $user->hasAbility('grant_api-write:action_sequence_schedule');
        });

        $gate->define('api-write:property', function ($user) {
            return $user->hasAbility('grant_api-write:property');
        });

        $gate->define('api-fetch:desired_states', function ($user) {
            return $user->hasAbility('grant_api-fetch:desired_states');
        });

        $gate->define('api-evaluate:critical_state', function ($user) {
            return $user->hasAbility('grant_api-evaluate:critical_state');
        });
    }
}