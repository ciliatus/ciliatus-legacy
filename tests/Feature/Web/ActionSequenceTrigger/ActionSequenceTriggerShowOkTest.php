<?php

namespace Tests\Feature\Web\ActionSequenceTrigger;

use App\ActionSequence;
use App\ActionSequenceTrigger;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceTriggerIndexOkTest
 * @package Tests\Feature
 */
class ActionSequenceTriggerShowOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $as = ActionSequence::create();
        $obj = ActionSequenceTrigger::create(['action_sequence_id' => $as->id]);
        $this->actingAs($user)->get('/action_sequence_triggers/' . $obj->id . '/delete')->assertStatus(200);
        $obj->delete();
        $as->delete();

        $this->cleanupUsers();

    }

}
