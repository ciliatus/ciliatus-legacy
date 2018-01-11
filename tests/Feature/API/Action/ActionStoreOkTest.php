<?php

namespace Tests\Feature\API\Action;

use App\ActionSequence;
use App\LogicalSensor;
use App\Action;
use App\PhysicalSensor;
use App\Terrarium;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionStoreOkTest
 * @package Tests\Feature
 */
class ActionStoreOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $terrarium = Terrarium::create([
            'name' => 'TestTerrarium01', 'display_name' => 'TestTerrarium01'
        ]);
        $action_sequence = ActionSequence::create(['terrarium_id' => $terrarium->id]);
        $valve = Valve::create(['name' => 'V01']);

        $response = $this->json('POST', '/api/v1/actions', [
            'action_sequence' => $action_sequence->id,
            'component' => 'Valve|' . $valve->id,
            'state' => 'running',
            'duration_minutes' => 10,
            'sequence_sort_id' => 1

        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $action_sequence->delete();
        $terrarium->delete();

        $this->cleanupUsers();

    }

}
