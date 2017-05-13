<?php

namespace Tests;

use App\User;
use App\UserAbility;
use Webpatser\Uuid\Uuid;

class CiliatusCase extends TestCase
{
    /**
     * @param bool $phpunit_ignore
     * @return bool|String
     */
    public function createUserReadOnly($phpunit_ignore = true)
    {
        if ($phpunit_ignore)
            return true;

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
     * @param bool $phpunit_ignore
     * @return String|bool
     */
    public function createUserFullPermissions($phpunit_ignore = true)
    {
        if ($phpunit_ignore)
            return true;

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
     * @param bool $phpunit_ignore
     * @return bool
     */
    public function cleanupUsers($phpunit_ignore = true)
    {
        if ($phpunit_ignore)
            return true;

        $user_names = ['phpunit-read'];

        foreach ($user_names as $user_name) {
            $user = User::where('name', $user_name)->get()->first();
            if (!is_null($user)) {
                $user->delete();
            }
        }

        return true;
    }
}
