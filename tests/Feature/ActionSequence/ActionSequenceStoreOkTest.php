<?php

namespace Tests\Feature\ActionSequence;

use App\LogicalSensor;
use App\ActionSequence;
use App\PhysicalSensor;
use App\Terrarium;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceStoreOkTest
 * @package Tests\Feature
 */
class ActionSequenceStoreOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $terrarium = Terrarium::create([
            'display_name' => 'TestTerrarium01'
        ]);

        $response = $this->json('POST', '/api/v1/action_sequences', [
            'terrarium' => $terrarium->id,
            'name' => 'TestActionSequence01',
            'template' => 'irrigate',
            'runonce' => false,
            'duration_minutes' => 1
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id'
            ]
        ]);

        $action_sequence_id = $response->decodeResponseJson()['data']['id'];

        $response = $this->json('GET', '/api/v1/action_sequences/' . $action_sequence_id);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $action_sequence_id
            ]
        ]);

        ActionSequence::find($action_sequence_id)->delete();
        $terrarium->delete();

        $this->cleanupUsers();

    }

}
