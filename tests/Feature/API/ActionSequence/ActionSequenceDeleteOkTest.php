<?php

namespace Tests\Feature\API\ActionSequence;

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
 * Class ActionSequenceDeleteOkTest
 * @package Tests\Feature
 */
class ActionSequenceDeleteOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $terrarium = Terrarium::create([
            'name' => 'TestTerrarium01', 'display_name' => 'TestTerrarium01'
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

        $terrarium->delete();

        $this->cleanupUsers();
    }

}
