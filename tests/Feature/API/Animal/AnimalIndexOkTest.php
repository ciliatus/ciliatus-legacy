<?php

namespace Tests\Feature\API\Animal;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalIndexOkTest
 * @package Tests\Feature
 */
class AnimalIndexOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $response = $this->json('GET', '/api/v1/animals', [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => []
        ]);

        $this->cleanupUsers();

    }

}
