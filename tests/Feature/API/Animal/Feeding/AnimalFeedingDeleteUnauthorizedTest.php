<?php

namespace Tests\Feature\API\AnimalFeeding;

use App\Animal;
use App\AnimalFeeding;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalFeedingDeleteUnauthorizedTest
 * @package Tests\Feature
 */
class AnimalFeedingDeleteUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token_full = $this->createUserFullPermissions();

        $animal = Animal::create([
            'name' => 'TestAnimalFeeding01', 'display_name' => 'TestAnimalFeeding01'
        ]);

        $response = $this->post('/api/v1/animals/' . $animal->id . '/feedings', [
            'meal_type' => 'Food',
            'created_at' => '2018-02-02'
        ],
        [
            'Authorization' => 'Bearer ' . $token_full
        ]);
        $response->assertStatus(200);
        $this->cleanupUsers();

        $id = $response->decodeResponseJson()['data']['id'];

        $response = $this->delete('/api/v1/animals/' . $animal->id . '/feedings/' . $id, [], [
            'Authorization' => 'Bearer ' . $this->createUserReadOnly()
        ]);
        $response->assertStatus(401);

        $animal->delete();

        $this->cleanupUsers();

    }

}
