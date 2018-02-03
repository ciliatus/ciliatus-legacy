<?php

namespace Tests\Feature\API\AnimalFeedingType;

use App\Animal;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalFeedingTypeStoreUnauthorizedTest
 * @package Tests\Feature
 */
class AnimalFeedingTypeStoreUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $response = $this->post('/api/v1/animals/feedings/types', [
            'name' => 'Food'
        ],
        [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $this->cleanupUsers();

    }

}
