<?php

namespace Tests\Feature\API\AnimalFeedingType;

use App\Animal;
use App\AnimalFeedingType;
use App\Property;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalFeedingTypeDeleteOkTest
 * @package Tests\Feature
 */
class AnimalFeedingTypeDeleteOkTest extends TestCase
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

        $response = $this->delete('/api/v1/animals/feedings/types/' . $id, [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $this->assertNull(Property::find($id));

        $this->cleanupUsers();

    }

}
