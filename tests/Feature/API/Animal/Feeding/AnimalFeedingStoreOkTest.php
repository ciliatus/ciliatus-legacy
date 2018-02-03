<?php

namespace Tests\Feature\API\AnimalFeeding;

use App\Animal;
use App\AnimalFeeding;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalFeedingStoreOkTest
 * @package Tests\Feature
 */
class AnimalFeedingStoreOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $animal = Animal::create([
            'name' => 'TestAnimalFeeding01', 'display_name' => 'TestAnimalFeeding01'
        ]);

        $response = $this->post('/api/v1/animals/' . $animal->id . '/feedings', [
            'meal_type' => 'Food',
            'count' => 1,
            'created_at' => '2018-02-02'
        ],
        [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $this->cleanupUsers();

    }

}
