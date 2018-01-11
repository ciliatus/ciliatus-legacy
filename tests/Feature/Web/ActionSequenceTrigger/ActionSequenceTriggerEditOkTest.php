<?php

namespace Tests\Feature\Web\ActionSequenceTrigger;

use App\ActionSequence;
use App\ActionSequenceTrigger;
use App\Terrarium;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceTriggerIndexOkTest
 * @package Tests\Feature
 */
class ActionSequenceTriggerEditOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $t = Terrarium::create(['display_name' => 'T01']);
        $as = ActionSequence::create(['terrarium_id' => $t->id]);
        $obj = ActionSequenceTrigger::create(['action_sequence_id' => $as->id]);
        $this->actingAs($user)->get('/action_sequence_triggers/' . $obj->id . '/edit')->assertStatus(200);
        $obj->delete();
        $as->delete();

        $this->cleanupUsers();

    }

}
