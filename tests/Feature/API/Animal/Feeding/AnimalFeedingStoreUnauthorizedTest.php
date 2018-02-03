<?php

namespace Tests\Feature\API\AnimalFeeding;

use App\Animal;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalFeedingStoreUnauthorizedTest
 * @package Tests\Feature
 */
class AnimalFeedingStoreUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $animal = Animal::create([
            'name' => 'TestAnimalFeeding01', 'display_name' => 'TestAnimalFeeding01'
        ]);

        $response = $this->post('/api/v1/animals/' . $animal->id . '/feedings', [
            'meal_type' => 'Food',
            'created_at' => '2018-02-02'
        ],
        [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $this->cleanupUsers();

    }

}
