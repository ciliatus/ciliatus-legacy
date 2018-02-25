<?php

namespace Tests\Feature\Web\Terrarium;

use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class DashboardTest
 * @package Tests\Feature
 */
class DashboardTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $response = $this->json('GET', '/api/v1/dashboard', [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                'controlunits' => [],
                'physical_sensors' => [],
                'terraria' => [],
                'animal_feeding_schedules' => [],
                'animal_weighing_schedules' => [],
                'action_sequence_schedules' => [],
                'action_sequence_triggers' => [],
                'action_sequence_intentions' => [],
                'suggestions' => []
            ]
        ]);

        $response->assertJsonStructure([
            'data' => [
                'terraria_ok_count'
            ]
        ]);

        $this->cleanupUsers();

    }

}
