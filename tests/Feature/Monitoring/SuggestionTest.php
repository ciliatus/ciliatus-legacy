<?php

namespace Tests\Feature\Monitoring;


use App\CriticalState;
use App\Event;
use App\LogicalSensor;
use App\PhysicalSensor;
use App\Terrarium;
use Carbon\Carbon;
use Artisan;
use Tests\TestHelperTrait;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SuggestionTest extends TestCase
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

        $terrarium->generateDefaultSuggestionSettings();

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

        //Create critical states
        $critical_states = [];
        for ($i = 0; $i < 31; $i++) {
            $cs = CriticalState::create([
                'belongsTo_type' => 'LogicalSensor',
                'belongsTo_id' => $logical_sensor->id,
                'is_soft_state' => false
            ]);

            $cs->created_at = Carbon::now()->subDays($i);
            $cs->updated_at = Carbon::now()->subDays($i);
            $cs->save();

            $critical_states[] = $cs;

        }

        Artisan::call('ciliatus:suggestions:generate', array('--terrarium_id' => $terrarium->id));

        $events = Event::where('belongsTo_type', 'Terrarium')
                       ->where('belongsTo_id', $terrarium->id)
                       ->where('type', 'Suggestion')
                       ->get();

        foreach ($critical_states as $cs) {
            $cs->delete();
        }
        $logical_sensor->delete();
        $physical_sensor->delete();
        $terrarium->delete();

        $this->assertTrue($events->count() > 0);

    }

}
