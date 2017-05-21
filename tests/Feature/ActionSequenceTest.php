<?php

namespace Tests\Feature;

use App\ActionSequence;
use App\Controlunit;
use App\Pump;
use App\Terrarium;
use App\Valve;
use Tests\CiliatusCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Webpatser\Uuid\Uuid;

class ActionSequenceTest extends CiliatusCase
{
    /**
     * Test unauthorized
     */
    public function test_000_Unauthenticated()
    {
        $response = $this->json('GET', '/api/v1/action_sequences');
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_100_IndexError()
    {
        $response = $this->json('GET', '/api/v1/action_sequences');
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_100_StoreError()
    {
        $token = $this->createUserReadOnly(false);

        $terrarium = Terrarium::create([
            'display_name' => 'TestTerrarium01'
        ]);

        $valve = Valve::create([
            'name' => 'TestValve01',
            'terrarium_id' => $terrarium->id
        ]);

        $response = $this->json('POST', '/api/v1/action_sequences', [
            'terrarium' => $terrarium->id,
            'name' => 'TestActionSequence01',
            'template' => 'irrigation',
            'runonce' => true,
            'duration_minutes' => 1
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $this->cleanupUsers();
    }

    /**
     *
     */
    public function test_200_IndexOk()
    {
        $token = $this->createUserReadOnly(false);

        $response = $this->json('GET', '/api/v1/action_sequences', [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => []
        ]);

        $this->cleanupUsers(false);
    }

    /**
     *
     */
    public function test_200_StoreError()
    {
        $token = $this->createUserFullPermissions(false);

        $response = $this->json('POST', '/api/v1/action_sequences', [
            'name' => 'TestActionSequence01',
            'template' => 'irrigation',
            'runonce' => true,
            'duration_minutes' => 1
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $response = $this->json('POST', '/api/v1/action_sequences', [
            'terrarium' => Uuid::generate()->string,
            'name' => 'TestActionSequence01',
            'template' => 'irrigation',
            'runonce' => true,
            'duration_minutes' => 1
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $this->cleanupUsers(false);
    }

    /**
     *
     */
    public function test_200_StoreOk()
    {
        $token = $this->createUserFullPermissions(false);

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

        $this->cleanupUsers(false);
    }

    /**
     *
     */
    public function test_300_UpdateError()
    {
        $token = $this->createUserReadOnly(false);

        $terrarium = Terrarium::create([
            'display_name' => 'TestTerrarium01'
        ]);

        $as = ActionSequence::create([
            'terrarium_id' => $terrarium->id,
            'name' => 'TestActionSequence01',
            'template' => 'irrigate',
            'runonce' => false,
            'duration_minutes' => 1
        ]);

        $response = $this->put('/api/v1/action_sequences/' . $as->id, [
            'name' => 'TestActionSequence02_Updated'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_400_UpdateOk()
    {
        $token = $this->createUserFullPermissions(false);

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

        $response = $this->put('/api/v1/action_sequences/' . $action_sequence_id, [
            'name' => 'TestActionSequence01_Updated',
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/action_sequences/' . $action_sequence_id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $action_sequence_id,
                'name' => 'TestActionSequence01_Updated'
            ]
        ]);

    }

    /**
     *
     */
    public function test_500_ShowOk()
    {
        $token = $this->createUserFullPermissions(false);

        $terrarium = Terrarium::create([
            'display_name' => 'TestTerrarium01'
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
        $action_sequence = ActionSequence::find($action_sequence_id);

        $response = $this->get('/api/v1/action_sequences/' . $action_sequence_id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $action_sequence_id,
                'name' => 'TestActionSequence01',
                'duration_minutes' => 1,
                'actions' => [
                    [
                        'id' => $action_sequence->actions->first()->id,
                        'target_type' => 'Valve',
                        'target_id' => $valve->id,
                        'desired_state' => 'running',
                        'duration_minutes' => 1
                    ]
                ],
                'terrarium' => [
                    'id' => $terrarium->id,
                    'display_name' => 'TestTerrarium01'
                ]
            ]
        ]);
    }

    /**
     *
     */
    public function test_500_DeleteError()
    {
        $token = $this->createUserReadOnly(false);

        $terrarium = Terrarium::create([
            'display_name' => 'TestTerrarium01'
        ]);

        $as = ActionSequence::create([
            'terrarium_id' => $terrarium->id,
            'name' => 'TestActionSequence01',
            'template' => 'irrigate',
            'runonce' => false,
            'duration_minutes' => 1
        ]);

        $response = $this->delete('/api/v1/action_sequences/' . $as->id, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_600_DeleteOk()
    {
        $token = $this->createUserFullPermissions(false);

        $terrarium = Terrarium::create([
            'display_name' => 'TestTerrarium01'
        ]);

        $as = ActionSequence::create([
            'terrarium_id' => $terrarium->id,
            'name' => 'TestActionSequence01',
            'template' => 'irrigate',
            'runonce' => false,
            'duration_minutes' => 1
        ]);

        $response = $this->delete('/api/v1/action_sequences/' . $as->id, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/action_sequences/' . $as->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(404);

    }
}
