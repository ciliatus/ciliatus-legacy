<?php

namespace Tests\Feature\API\User;


use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class UserUpdateUnauthorizedTest
 * @package Tests\Feature
 */
class UserUpdateUnauthorizedTest extends TestCase
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

        $response = $this->put('/api/v1/users/' . $user->id, [
            'name' => 'User01_Updated',
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $user->delete();

        $this->cleanupUsers();

    }

}
