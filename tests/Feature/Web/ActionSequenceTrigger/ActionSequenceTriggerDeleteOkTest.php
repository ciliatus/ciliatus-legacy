<?php

namespace Tests\Feature\Web\ActionSequenceTrigger;

use App\ActionSequenceTrigger;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceTriggerIndexOkTest
 * @package Tests\Feature
 */
class ActionSequenceTriggerDeleteOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $obj = ActionSequenceTrigger::create(['starts_at' => '08:00']);
        $this->actingAs($user)->get('/action_sequence_triggers/' . $obj->id . '/delete')->assertStatus(200);
        $obj->delete();

        $this->cleanupUsers();

    }

}
