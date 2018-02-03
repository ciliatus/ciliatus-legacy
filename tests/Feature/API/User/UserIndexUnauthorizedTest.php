<?php

namespace Tests\Feature\API\User;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class UserIndexUnauthorizedTest
 * @package Tests\Feature
 */
class UserIndexUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {


        $token = $this->createUserReadOnly();

        $response = $this->get('/api/v1/users', [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $this->cleanupUsers();

    }

}
