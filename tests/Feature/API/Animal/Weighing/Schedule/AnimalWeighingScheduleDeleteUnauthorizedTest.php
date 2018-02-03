<?php

namespace Tests\Feature\API\AnimalWeighingSchedule;

use App\Animal;
use App\AnimalWeighingSchedule;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalWeighingScheduleDeleteUnauthorizedTest
 * @package Tests\Feature
 */
class AnimalWeighingScheduleDeleteUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token_full = $this->createUserFullPermissions();

        $animal = Animal::create([
            'name' => 'TestAnimalWeighingSchedule01', 'display_name' => 'TestAnimalWeighingSchedule01'
        ]);

        $response = $this->post('/api/v1/animals/' . $animal->id . '/weighing_schedules', [
            'value' => '7'
        ],
        [
            'Authorization' => 'Bearer ' . $token_full
        ]);
        $response->assertStatus(200);
        $this->cleanupUsers();

        $id = $response->decodeResponseJson()['data']['id'];

        $response = $this->delete('/api/v1/animals/' . $animal->id . '/weighing_schedules/' . $id, [], [
            'Authorization' => 'Bearer ' . $this->createUserReadOnly()
        ]);
        $response->assertStatus(401);

        $animal->delete();

        $this->cleanupUsers();

    }

}
