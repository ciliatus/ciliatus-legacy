<?php

namespace Tests\Feature\API\Animal;

use App\LogicalSensor;
use App\PhysicalSensor;
use App\Sensorreading;
use App\Terrarium;
use Artisan;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalShowOkTest
 * @package Tests\Feature
 */
class ArtisanCommandsTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $terrarium = Terrarium::create([
            'name' => 'Terrarium01'
        ]);
        $ps = PhysicalSensor::create([
            'name' => 'PS01',
            'belongsTo_type' => 'Terrarium',
            'belongsTo_id' => $terrarium->id
        ]);
        $ls = LogicalSensor::create([
            'name' => 'LS01',
            'physical_sensor_id' => $ps->id,
            'type' => 'humidity_percent'
        ]);
        $sr = Sensorreading::create([
            'sensorreadinggroup_id' => Uuid::uuid4(),
            'logical_sensor_id' => $ls->id,
            'rawvalue' => 43
        ]);

        $this->assertEquals(0, Artisan::call('ciliatus:cache:rebuild'));
        $this->assertEquals(0, Artisan::call('ciliatus:caresheets:generate'));
        $this->assertEquals(0, Artisan::call('ciliatus:convert_lang_to_json'));
        $this->assertEquals(0, Artisan::call('ciliatus:critical_states:evaluate'));
        $this->assertEquals(0, Artisan::call('ciliatus:detect_sensorreading_anomalies'));
        $this->assertEquals(0, Artisan::call('ciliatus:notifications:send'));
        $this->assertEquals(0, Artisan::call('ciliatus:suggestions:generate'));

        $sr->delete();
        $ls->delete();
        $ps->delete();
        $terrarium->delete();

    }

}
