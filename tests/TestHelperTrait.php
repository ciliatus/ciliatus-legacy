<?php

namespace Tests;

use App\User;
use App\UserAbility;
use Webpatser\Uuid\Uuid;

trait TestHelperTrait
{

    /**
     * @return mixed
     * @throws \Exception
     */
    public function createUserWeb()
    {
        $user = User::where('name', 'phpunit-web')->get()->first();
        if (!is_null($user)) {
            $user->delete();
        }

        ;
        $user = User::create([
            'name' => 'phpunit-web',
            'email' => 'phpunit-web@ciliatus.io',
            'password' => bcrypt(Uuid::generate()),
            'locale' => 'en'
        ]);

        return $user;
    }

    /**
     * @return User
     * @throws \Exception
     */
    public function createUserAdmin()
    {
        $user = User::where('name', 'phpunit-admin')->get()->first();
        if (!is_null($user)) {
            $user->delete();
        }

        /**
         * @var User $user
         */
        $user = User::create([
            'name' => 'phpunit-admin',
            'email' => 'phpunit-admin@ciliatus.io',
            'password' => bcrypt(Uuid::generate()),
            'locale' => 'en'
        ]);

        $user->grantFullAbilities();

        return $user;
    }

    /**
     * @return bool|String
     * @throws \Exception
     */
    public function createUserNothing()
    {
        $user = User::where('name', 'phpunit-nothing')->get()->first();
        if (!is_null($user)) {
            $user->delete();
        }

        $user = User::create([
            'name' => 'phpunit-nothing',
            'email' => 'phpunit-nothing@ciliatus.io',
            'password' => bcrypt(Uuid::generate()),
            'locale' => 'en'
        ]);

        return $user->createToken('phpunit-nothing')->accessToken;
    }

    /**
     * @return bool|String
     * @throws \Exception
     */
    public function createUserReadOnly()
    {
        $user = User::where('name', 'phpunit-read')->get()->first();
        if (!is_null($user)) {
            $user->delete();
        }

        $user = User::create([
            'name' => 'phpunit-read',
            'email' => 'phpunit-read@ciliatus.io',
            'password' => bcrypt(Uuid::generate()),
            'locale' => 'en'
        ]);

        UserAbility::create([
            'user_id' => $user->id,
            'name' => 'grant_api-read'
        ]);

        UserAbility::create([
            'user_id' => $user->id,
            'name' => 'grant_api-list'
        ]);

        return $user->createToken('phpunit-read')->accessToken;
    }

    /**
     * @return String|bool
     * @throws \Exception
     */
    public function createUserFullPermissions()
    {
        $user = User::where('name', 'phpunit-full')->get()->first();
        if (!is_null($user)) {
            $user->delete();
        }

        $user = User::create([
            'name' => 'phpunit-full',
            'email' => 'phpunit-full@ciliatus.io',
            'password' => bcrypt(Uuid::generate()),
            'locale' => 'en'
        ]);

        $user->grantFullAbilities();

        return $user->createToken('phpunit-full')->accessToken;
    }

    /**
     * @return bool
     */
    public function cleanupUsers()
    {
        $user_names = ['phpunit-nothing', 'phpunit-read', 'phpunit-full'];

        foreach ($user_names as $user_name) {
            $user = User::where('name', $user_name)->get()->first();
            if (!is_null($user)) {
                $user->delete();
            }
        }

        return true;
    }

}
