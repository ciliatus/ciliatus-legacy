<?php

namespace Tests\Feature\Web\Action;

use App\Action;
use App\ActionSequence;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionIndexOkTest
 * @package Tests\Feature
 */
class ActionDeleteOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $valve = Valve::create(['name' => 'V01']);
        $sequence = ActionSequence::create();
        $obj = Action::create([
            'action_sequence_id' => $sequence->id,
            'target_type' => 'Valve',
            'target_id' => $valve->id,
            'desired_state' => 'open',
            'duration_minutes' => 10,
            'sequence_sort_id' => 1
        ]);
        $this->actingAs($user)->get('/actions/' . $obj->id . '/delete')->assertStatus(200);
        $obj->delete();
        $sequence->delete();
        $valve->delete();

        $this->cleanupUsers();

    }

}
