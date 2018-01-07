<?php

namespace Tests\Feature\Web\ActionSequence;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceIndexOkTest
 * @package Tests\Feature
 */
class ActionSequenceCreateOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $this->actingAs($user)->get('/action_sequences/create')->assertStatus(200);

        $this->cleanupUsers();

    }

}
