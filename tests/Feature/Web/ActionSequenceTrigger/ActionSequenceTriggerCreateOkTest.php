<?php

namespace Tests\Feature\Web\ActionSequenceTrigger;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceTriggerIndexOkTest
 * @package Tests\Feature
 */
class ActionSequenceTriggerCreateOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $this->actingAs($user)->get('/action_sequence_triggers/create')->assertStatus(200);

        $this->cleanupUsers();

    }

}
