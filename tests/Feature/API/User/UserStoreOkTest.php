<?php

namespace Tests\Feature\API\User;

use App\User;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class UserStoreOkTest
 * @package Tests\Feature
 */
class UserStoreOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        User::where('name', 'User01')->orWhere('email', 'User01@test')->delete();
        $response = $this->post('/api/v1/users', [
            'name' => 'User01',
            'email' => 'User01@test',
            'password' => 'user01',
            'password_2' => 'user01'
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id'
            ]
        ]);

        $id = $response->decodeResponseJson()['data']['id'];

        $this->assertNotNull(User::find($id));

        User::find($id)->delete();

        $this->cleanupUsers();

    }

}
