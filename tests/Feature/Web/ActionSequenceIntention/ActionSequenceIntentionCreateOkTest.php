<?php

namespace Tests\Feature\Web\ActionSequenceIntention;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceIntentionIndexOkTest
 * @package Tests\Feature
 */
class ActionSequenceIntentionCreateOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $this->actingAs($user)->get('/action_sequence_intentions/create')->assertStatus(200);

        $this->cleanupUsers();

    }

}
