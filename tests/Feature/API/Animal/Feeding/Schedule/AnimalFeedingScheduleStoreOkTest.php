<?php

namespace Tests\Feature\API\AnimalFeedingSchedule;

use App\Animal;
use App\AnimalFeedingSchedule;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalFeedingScheduleStoreOkTest
 * @package Tests\Feature
 */
class AnimalFeedingScheduleStoreOkTest extends TestCase
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

        $this->cleanupUsers();

    }

}
