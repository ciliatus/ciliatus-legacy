<?php

namespace Tests\Feature\Automation;

use App\Controlunit;
use App\System;
use App\LogicalSensor;
use App\PhysicalSensor;
use App\Pump;
use App\Terrarium;
use App\Valve;
use Carbon\Carbon;
use Tests\TestHelperTrait;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmergencyStopTest extends TestCase
{

    use TestHelperTrait;

    /**
     *
     */
    public function test_CreateActionSequenceWithEnablersAndFetch()
    {
        $token = $this->createUserFullPermissions();

        $terrarium = Terrarium::create([
            'name' => 'TestTerrarium01', 'display_name' => 'TestTerrarium01'
        ]);

        $controlunit = Controlunit::create([
            'name' => 'Controlunit01'
        ]);

        $pump = Pump::create([
            'name' => 'TestValve01',
            'controlunit_id' => $controlunit->id
        ]);

        $valve = Valve::create([
            'name' => 'TestValve01',
            'terrarium_id' => $terrarium->id,
            'controlunit_id' => $controlunit->id,
            'pump_id' => $pump->id
        ]);

        $physical_sensor = PhysicalSensor::create([
            'name' => 'PhysicalSensor01',
            'belongsTo_type' => 'Terrarium',
            'belongsTo_id' => $terrarium->id,
            'controlunit_id' => $controlunit->id
        ]);

        $logical_sensor = LogicalSensor::create([
            'name' => 'LogicalSensor01',
            'physical_sensor_id' => $physical_sensor->id,
            'type' => 'humidity_percent'
        ]);

        $logical_sensor->rawvalue = 40;
        $logical_sensor->save();


        /*
         * Create sequence with template
         */

        $response = $this->json('POST', '/api/v1/action_sequences', [
            'terrarium' => $terrarium->id,
            'name' => 'TestActionSequence01',
            'template' => 'irrigate',
            'runonce' => false,
            'duration_minutes' => 1
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id'
            ]
        ]);

        $action_sequence_id = $response->decodeResponseJson()['data']['id'];

        $response = $this->json('GET', '/api/v1/controlunits/' . $controlunit->id . '/fetch_desired_states', [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'Valve' => [],
                'Pump' => []
            ]
        ]);


        /*
         * Create schedule
         */

        $response = $this->json('POST', '/api/v1/action_sequence_schedules', [
            'action_sequence' => $action_sequence_id,
            'starts_at' => Carbon::now()->subMinutes(1)->format('H:i:s'),
            'runonce' => true,
            'duration_minutes' => 1
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id'
            ]
        ]);


        $response = $this->json('GET', '/api/v1/controlunits/' . $controlunit->id . '/fetch_desired_states');
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'Valve' => [
                    $valve->id => 'running'
                ],
                'Pump' => [
                    $pump->id => 'running'
                ]
            ]
        ]);

        $this->post(url('api/v1/action_sequences/stop_all'));

        $response = $this->json('GET', '/api/v1/controlunits/' . $controlunit->id . '/fetch_desired_states');
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'Valve' => [],
                'Pump' => []
            ]
        ]);

        $this->cleanupUsers();
    }

}
