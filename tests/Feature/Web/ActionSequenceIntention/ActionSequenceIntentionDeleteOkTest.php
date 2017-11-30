<?php

namespace Tests\Feature\Web\ActionSequenceIntention;

use App\ActionSequenceIntention;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceIntentionIndexOkTest
 * @package Tests\Feature
 */
class ActionSequenceIntentionDeleteOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $obj = ActionSequenceIntention::create();
        $this->actingAs($user)->get('/action_sequence_intentions/' . $obj->id . '/delete')->assertStatus(200);
        $obj->delete();

        $this->cleanupUsers();

    }

}
