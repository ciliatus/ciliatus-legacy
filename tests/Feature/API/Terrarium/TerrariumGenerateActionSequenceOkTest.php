<?php

namespace Tests\Feature\API\Terrarium;

use App\ActionSequence;
use App\GenericComponent;
use App\GenericComponentType;
use App\PhysicalSensor;
use App\Terrarium;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use phpDocumentor\Reflection\DocBlock\Tags\Generic;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class TerrariumGenerateActionSequenceOkTest
 * @package Tests\Feature
 */
class TerrariumGenerateActionSequenceOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $terrarium = Terrarium::create([
            'name' => 'TestTerrarium01', 'display_name' => 'TestTerrarium01'
        ]);

        $valve = Valve::create([
            'name' => 'V',
            'terrarium_id' => $terrarium->id
        ]);

        $response = $this->post('/api/v1/terraria/' . $terrarium->id . '/action_sequence',  [
            'runonce' => 'On',
            'template' => 'irrigate',
            'duration_minutes' => 10,
            'schedule_now' => 'On'
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => []
        ]);

        $id = $response->decodeResponseJson()['data']['id'];

        $this->assertNotNull(ActionSequence::find($id));
        ActionSequence::find($id)->delete();

        $valve->delete();
        $terrarium->delete();

        $this->cleanupUsers();

    }

}
