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
                'controlunits' => [
                    'critical' => []
                ],
                'physical_sensors' => [
                    'critical' => []
                ],
                'terraria' => [
                    'ok' => [],
                    'critical' => []
                ],
                'animal_feeding_schedules' => [
                    'due' => [],
                    'overdue' => []
                ],
                'animal_weighing_schedules' => [
                    'due' => [],
                    'overdue' => []
                ],
                'action_sequence_schedules' => [
                    'due' => [],
                    'overdue' => [],
                    'running' => []
                ],
                'action_sequence_triggers' => [
                    'running' => [],
                    'should_be_running' => []
                ],
                'action_sequence_intentions' => [
                    'running' => [],
                    'should_be_running' => []
                ],
                'suggestions' => []
            ]
        ]);

        $this->cleanupUsers();

    }

}
