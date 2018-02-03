<?php

namespace Tests\Feature\API\AnimalFeedingSchedule;

use App\Animal;
use App\AnimalFeedingSchedule;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalFeedingScheduleDeleteUnauthorizedTest
 * @package Tests\Feature
 */
class AnimalFeedingScheduleDeleteUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token_full = $this->createUserFullPermissions();

        $animal = Animal::create([
            'name' => 'TestAnimalFeedingSchedule01', 'display_name' => 'TestAnimalFeedingSchedule01'
        ]);

        $response = $this->post('/api/v1/animals/' . $animal->id . '/feeding_schedules', [
            'meal_type' => 'Food',
            'value' => '7'
        ],
        [
            'Authorization' => 'Bearer ' . $token_full
        ]);
        $response->assertStatus(200);
        $this->cleanupUsers();

        $id = $response->decodeResponseJson()['data']['id'];

        $response = $this->delete('/api/v1/animals/' . $animal->id . '/feeding_schedules/' . $id, [], [
            'Authorization' => 'Bearer ' . $this->createUserReadOnly()
        ]);
        $response->assertStatus(401);

        $animal->delete();

        $this->cleanupUsers();

    }

}
