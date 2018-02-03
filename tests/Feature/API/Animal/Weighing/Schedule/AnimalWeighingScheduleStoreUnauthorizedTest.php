<?php

namespace Tests\Feature\API\AnimalWeighingSchedule;

use App\Animal;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalWeighingScheduleStoreUnauthorizedTest
 * @package Tests\Feature
 */
class AnimalWeighingScheduleStoreUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $animal = Animal::create([
            'name' => 'TestAnimalWeighingSchedule01', 'display_name' => 'TestAnimalWeighingSchedule01'
        ]);

        $response = $this->post('/api/v1/animals/' . $animal->id . '/weighing_schedules', [
            'value' => '7'
        ],
        [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $this->cleanupUsers();

    }

}
