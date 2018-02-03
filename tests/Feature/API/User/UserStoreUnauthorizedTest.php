<?php

namespace Tests\Feature\API\User;

use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class UserStoreUnauthorizedTest
 * @package Tests\Feature
 */
class UserStoreUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        User::where('name', 'User01')->orWhere('email', 'User01@test')->delete();
        $response = $this->json('POST', '/api/v1/users', [
            'name' => 'User01',
            'email' => 'User01@test',
            'password' => bcrypt('user01')
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $this->cleanupUsers();

    }

}
