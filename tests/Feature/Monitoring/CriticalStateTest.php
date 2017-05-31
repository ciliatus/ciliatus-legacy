<?php

namespace Tests\Feature;


use App\CriticalState;
use App\Event;
use App\LogicalSensor;
use App\LogicalSensorThreshold;
use App\PhysicalSensor;
use App\Sensorreading;
use App\Terrarium;
use Carbon\Carbon;
use Artisan;
use Tests\TestHelperTrait;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Webpatser\Uuid\Uuid;

class CriticalStateTest extends TestCase
{

    use TestHelperTrait;

    /**
     *
     */
    public function test()
    {

        $terrarium = Terrarium::create([
            'name' => 'TestTerrarium01', 'display_name' => 'TestTerrarium01'
        ]);

        $physical_sensor = PhysicalSensor::create([
            'name' => 'TestPs01',
            'belongsTo_type' => 'Terrarium',
            'belongsTo_id' => $terrarium->id
        ]);

        $logical_sensor = LogicalSensor::create([
            'name' => 'TestLs01',
            'physical_sensor_id' => $physical_sensor->id,
            'type' => 'humidity_percent'
        ]);

        $logical_sensor->soft_state_duration_minutes = 1;
        $logical_sensor->save();

        $logical_sensor_threshold = LogicalSensorThreshold::create([
            'logical_sensor_id' => $logical_sensor->id,
            'starts_at' => '00:00:00',
            'rawvalue_lowerlimit' => 80,
            'active' => true
        ]);

        //Create sensorreadings
        $sensorreadings = [];
        for ($i = 0; $i < 30; $i++) {

            $sensorreadings[] = Sensorreading::create([
                'sensorreadinggroup_id' => Uuid::generate(),
                'logical_sensor_id' => $logical_sensor->id,
                'rawvalue' => 10,
                'created_at' => Carbon::now()->subMinutes($i)
            ]);

        }

        $logical_sensor->rawvalue = 10;
        $logical_sensor->save();


        /*
         * Check if soft for < 2 mins and then check if hardstate
         */
        $timeout = 15;

        for ($i = 0; $i < 10; $i++) {

            $sensorreadings[] = Sensorreading::create([
                'sensorreadinggroup_id' => Uuid::generate(),
                'logical_sensor_id' => $logical_sensor->id,
                'rawvalue' => 10,
                'created_at' => Carbon::now()
            ]);

            Artisan::call('ciliatus:critical_states:evaluate');

            $critical_states = CriticalState::where('belongsTo_type', 'LogicalSensor')
                ->where('belongsTo_id', $logical_sensor->id)
                ->where(function ($query) use ($i, $timeout) {
                    $query->where('is_soft_state',  ($i * $timeout <= 120))
                        ->orWhere('is_soft_state', !($i * $timeout >= 60));
                })
                ->get();

            $this->assertTrue($critical_states->count() > 0);

            sleep($timeout);

        }

        /*
         * Check recovery
         */
        $sensorreadings[] = Sensorreading::create([
            'sensorreadinggroup_id' => Uuid::generate(),
            'logical_sensor_id' => $logical_sensor->id,
            'rawvalue' => 99,
            'created_at' => Carbon::now()
        ]);

        $logical_sensor->rawvalue = 99;
        $logical_sensor->save();

        sleep(15);

        Artisan::call('ciliatus:critical_states:evaluate');

        $critical_states = CriticalState::where('belongsTo_type', 'LogicalSensor')
            ->where('belongsTo_id', $logical_sensor->id)
            ->where('recovered_at', '>', Carbon::now()->subMinutes(2))
            ->get();

        $this->assertTrue($critical_states->count() > 0);

        foreach ($critical_states as $cs) {
            #$cs->delete();
        }
        foreach ($sensorreadings as $sr) {
            #$sr->delete();
        }
        #$logical_sensor_threshold->delete();
        #$logical_sensor->delete();
        #$physical_sensor->delete();
        #$terrarium->delete();

    }

}
