<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

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
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin', function ($user) {
            return $user->hasAbility('grant_admin');
        });

        Gate::define('api-read', function ($user) {
            return $user->hasAbility('grant_api-read');
        });

        Gate::define('api-read_admin', function ($user) {
            return $user->hasAbility('grant_api-read_admin');
        });

        Gate::define('api-list', function ($user) {
            return $user->hasAbility('grant_api-list');
        });

        Gate::define('api-list:raw', function ($user) {
            return $user->hasAbility('grant_api-list:raw');
        });

        Gate::define('api-list_admin', function ($user) {
            return $user->hasAbility('grant_api-list_admin');
        });

        Gate::define('api-write:sensorreading', function ($user) {
            return $user->hasAbility('grant_api-write:sensorreading');
        });

        Gate::define('api-write:terrarium', function ($user) {
            return $user->hasAbility('grant_api-write:terrarium');
        });

        Gate::define('api-write:caresheet', function ($user) {
            return $user->hasAbility('grant_api-write:caresheet');
        });

        Gate::define('api-write:animal', function ($user) {
            return $user->hasAbility('grant_api-write:animal');
        });

        Gate::define('api-write:animal_feeding', function ($user) {
            return $user->hasAbility('grant_api-write:animal_feeding');
        });

        Gate::define('api-write:animal_feeding_schedule', function ($user) {
            return $user->hasAbility('grant_api-write:animal_feeding_schedule');
        });

        Gate::define('api-write:animal_weighing', function ($user) {
            return $user->hasAbility('grant_api-write:animal_weighing');
        });

        Gate::define('api-write:animal_weighing_schedule', function ($user) {
            return $user->hasAbility('grant_api-write:animal_weighing_schedule');
        });

        Gate::define('api-write:valve', function ($user) {
            return $user->hasAbility('grant_api-write:valve');
        });

        Gate::define('api-write:pump', function ($user) {
            return $user->hasAbility('grant_api-write:pump');
        });

        Gate::define('api-write:physical_sensor', function ($user) {
            return $user->hasAbility('grant_api-write:physical_sensor');
        });

        Gate::define('api-write:logical_sensor', function ($user) {
            return $user->hasAbility('grant_api-write:logical_sensor');
        });

        Gate::define('api-write:generic_component', function ($user) {
            return $user->hasAbility('grant_api-write:generic_component');
        });

        Gate::define('api-write:generic_component_type', function ($user) {
            return $user->hasAbility('grant_api-write:generic_component_type');
        });

        Gate::define('api-write:controlunit', function ($user) {
            return $user->hasAbility('grant_api-write:controlunit');
        });

        Gate::define('api-write:file', function ($user) {
            return $user->hasAbility('grant_api-write:file');
        });

        Gate::define('api-write:file_propertiy', function ($user) {
            return $user->hasAbility('grant_api-write:file_propertiy');
        });

        Gate::define('api-write:logical_sensor_threshold', function ($user) {
            return $user->hasAbility('grant_api-write:logical_sensor_threshold');
        });

        Gate::define('api-write:user_self', function ($user) {
            return $user->hasAbility('grant_api-write:user_self');
        });

        Gate::define('api-write:user_all', function ($user) {
            return $user->hasAbility('grant_api-write:user_all');
        });

        Gate::define('api-write:user_ability', function ($user) {
            return $user->hasAbility('grant_api-write:user_ability');
        });

        Gate::define('api-write:user_telegram', function ($user) {
            return $user->hasAbility('grant_api-write:user_telegram');
        });

        Gate::define('api-write:action', function ($user) {
            return $user->hasAbility('grant_api-write:action');
        });

        Gate::define('api-write:action_sequence', function ($user) {
            return $user->hasAbility('grant_api-write:action_sequence');
        });

        Gate::define('api-write:action_sequence_schedule', function ($user) {
            return $user->hasAbility('grant_api-write:action_sequence_schedule');
        });

        Gate::define('api-write:action_sequence_trigger', function ($user) {
            return $user->hasAbility('grant_api-write:action_sequence_trigger');
        });

        Gate::define('api-write:action_sequence_intention', function ($user) {
            return $user->hasAbility('grant_api-write:action_sequence_intention');
        });

        Gate::define('api-write:property', function ($user) {
            return $user->hasAbility('grant_api-write:property');
        });

        Gate::define('api-write:property', function ($user) {
            return $user->hasAbility('grant_api-write:property');
        });

        Gate::define('api-write:biography_entry', function ($user) {
            return $user->hasAbility('grant_api-write:biography_entry');
        });

        Gate::define('api-write:reminder', function ($user) {
            return $user->hasAbility('grant_api-write:reminder');
        });

        Gate::define('api-fetch:desired_states', function ($user) {
            return $user->hasAbility('grant_api-fetch:desired_states');
        });

        Gate::define('api-evaluate:critical_state', function ($user) {
            return $user->hasAbility('grant_api-evaluate:critical_state');
        });

        Passport::routes();
    }
}
