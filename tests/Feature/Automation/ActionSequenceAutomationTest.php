<?php

namespace Tests\Feature\Automation;

use App\Controlunit;
use App\CriticalState;
use App\LogicalSensor;
use App\PhysicalSensor;
use App\Pump;
use App\Sensorreading;
use App\Terrarium;
use App\Valve;
use Carbon\Carbon;
use Tests\TestHelperTrait;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Webpatser\Uuid\Uuid;

class ActionSequenceAutomationTest extends TestCase
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

        $response = $this->json('GET', '/api/v1/controlunits/' . $controlunit->id . '/fetch_desired_states');
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

        sleep(61);

        $response = $this->json('GET', '/api/v1/controlunits/' . $controlunit->id . '/fetch_desired_states');
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'Valve' => [],
                'Pump' => []
            ]
        ]);


        /*
         * Create trigger
         */

        $response = $this->json('POST', '/api/v1/action_sequence_triggers', [
            'logical_sensor' => $logical_sensor->id,
            'reference_value' => 50,
            'reference_value_comparison_type' => 'lesser',
            'reference_value_duration_threshold_minutes' => 1,
            'minimum_timeout_minutes' => 30,
            'timeframe_start' => '00:00:00',
            'timeframe_end' => '23:59:59',
            'action_sequence' => $action_sequence_id
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id'
            ]
        ]);

        Sensorreading::create([
            'sensorreadinggroup_id' => Uuid::generate()->string,
            'logical_sensor_id' => $logical_sensor->id,
            'rawvalue' => 40,
            'created_at' => Carbon::now()->subMinutes(30)
        ]);

        Sensorreading::create([
            'sensorreadinggroup_id' => Uuid::generate()->string,
            'logical_sensor_id' => $logical_sensor->id,
            'rawvalue' => 40,
            'created_at' => Carbon::now()->subMinutes(20)
        ]);

        Sensorreading::create([
            'sensorreadinggroup_id' => Uuid::generate()->string,
            'logical_sensor_id' => $logical_sensor->id,
            'rawvalue' => 40,
            'created_at' => Carbon::now()->subMinutes(10)
        ]);

        Sensorreading::create([
            'sensorreadinggroup_id' => Uuid::generate()->string,
            'logical_sensor_id' => $logical_sensor->id,
            'rawvalue' => 40,
            'created_at' => Carbon::now()
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

        sleep(61);

        $response = $this->json('GET', '/api/v1/controlunits/' . $controlunit->id . '/fetch_desired_states');
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'Valve' => [],
                'Pump' => []
            ]
        ]);



        /*
         * Create intentions
         */

        $response = $this->json('POST', '/api/v1/action_sequence_intentions', [
            'type' => 'humidity_percent',
            'intention' => 'increase',
            'minimum_timeout_minutes' => 10,
            'timeframe_start' => '00:00:00',
            'timeframe_end' => '23:59:59',
            'action_sequence' => $action_sequence_id
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id'
            ]
        ]);

        $response = $this->post('/api/v1/logical_sensor_thresholds', [
            'logical_sensor' => $logical_sensor->id,
            'starts_at' => '00:00:00',
            'lowerlimit' => 90,
            'active' => true
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id'
            ]
        ]);

        $critical_state = CriticalState::create([
            'belongsTo_type' => 'LogicalSensor',
            'belongsTo_id' => $logical_sensor->id,
            'is_soft_state' => false
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

        sleep(61);

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
