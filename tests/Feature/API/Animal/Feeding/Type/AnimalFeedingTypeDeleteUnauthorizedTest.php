<?php

namespace Tests\Feature\API\AnimalFeedingType;

use App\Animal;
use App\AnimalFeedingType;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalFeedingTypeDeleteUnauthorizedTest
 * @package Tests\Feature
 */
class AnimalFeedingTypeDeleteUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $response = $this->post('/api/v1/animals/feedings/types', [
            'name' => 'Food'
        ],
        [
            'Authorization' => 'Bearer ' . $this->createUserFullPermissions()
        ]);
        $response->assertStatus(200);
        $this->cleanupUsers();

        $id = $response->decodeResponseJson()['data']['id'];

        $response = $this->delete('/api/v1/animals/feedings/types/' . $id, [], [
            'Authorization' => 'Bearer ' . $this->createUserReadOnly()
        ]);
        $response->assertStatus(401);

        $this->cleanupUsers();

    }

}
