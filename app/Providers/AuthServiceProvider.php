<?php

namespace App\Providers;

use App\UserAbility;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
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

        Passport::routes();

        foreach (UserAbility::abilities() as $ability) {
            $exploded = explode('_', $ability);
            $type = $exploded[0];
            $perm = $exploded;
            $perm = implode('_', array_splice($perm, 1, count($exploded)-1));

            if ($type != 'grant') {
                continue;
            }

            Gate::define($perm, function ($user) use ($ability, $perm) {
                return $user->hasAbility($ability) && !$user->hasAbility('deny_' . $perm);
            });
        }
    }
}