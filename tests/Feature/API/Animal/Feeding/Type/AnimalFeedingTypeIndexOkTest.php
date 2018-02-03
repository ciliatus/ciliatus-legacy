<?php

namespace Tests\Feature\API\AnimalFeedingType;

use App\Animal;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalFeedingTypeIndexOkTest
 * @package Tests\Feature
 */
class AnimalFeedingTypeIndexOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $response = $this->post('/api/v1/animals/feedings/types', [
            'name' => 'Food'
        ],
        [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $id = $response->decodeResponseJson()['data']['id'];

        $response = $this->json('GET', '/api/v1/animals/feedings/types', [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => []
        ]);

        $this->cleanupUsers();

    }

}
