<?php

namespace Tests\Feature\API\User;

use App\Controlunit;
use App\User;
use App\Terrarium;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class UserUpdateOkTest
 * @package Tests\Feature
 */
class UserUpdateOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

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
        $response->assertStatus(200);

        $user = User::find($user->id);
        $this->assertEquals($user->name, 'User01_Updated');

        $user->fresh()->delete();

        $this->cleanupUsers();

    }

}
