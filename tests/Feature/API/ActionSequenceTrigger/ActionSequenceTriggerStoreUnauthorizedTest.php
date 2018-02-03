<?php

namespace Tests\Feature\API\ActionSequenceTrigger;

use App\ActionSequence;
use App\ActionSequenceTrigger;
use App\Terrarium;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceTriggerStoreUnauthorizedTest
 * @package Tests\Feature
 */
class ActionSequenceTriggerStoreUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $terrarium = Terrarium::create([
            'name' => 'TestTerrarium01', 'display_name' => 'TestTerrarium01'
        ]);

        $as = ActionSequence::create([
            'terrarium_id' => $terrarium->id,
            'name' => 'TestActionSequenceTrigger01',
            'template' => 'irrigate',
            'runonce' => false,
            'duration_minutes' => 1
        ]);

        $response = $this->post('/api/v1/action_sequence_triggers', [
            'name' => 'TestActionSequenceTrigger02_Updated'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $as->delete();
        $terrarium->delete();

        $this->cleanupUsers();

    }

}
