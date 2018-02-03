<?php

namespace Tests\Feature\API\AnimalFeedingSchedule;

use App\Animal;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalFeedingScheduleIndexOkTest
 * @package Tests\Feature
 */
class AnimalFeedingScheduleIndexOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $animal = Animal::create([
            'name' => 'TestAnimalFeedingSchedule01', 'display_name' => 'TestAnimalFeedingSchedule01'
        ]);

        $response = $this->post('/api/v1/animals/' . $animal->id . '/feeding_schedules', [
            'meal_type' => 'Food',
            'value' => '7'
        ],
        [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $id = $response->decodeResponseJson()['data']['id'];

        $response = $this->json('GET', '/api/v1/animals/' . $animal->id . '/feeding_schedules', [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => []
        ]);

        $this->cleanupUsers();

    }

}
