<?php

namespace Tests\Feature\API\User;

use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class UserDeleteUnauthorizedTest
 * @package Tests\Feature
 */
class UserDeleteUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        User::where('name', 'User01')->orWhere('email', 'User01@test')->delete();
        $user = User::create([
            'name' => 'User01',
            'email' => 'User01@test',
            'password' => bcrypt('user01')
        ]);

        $response = $this->delete('/api/v1/users/' . $user->id, [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $user->delete();

        $this->cleanupUsers();

    }

}
