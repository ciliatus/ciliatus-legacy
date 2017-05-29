<?php

namespace Tests\Feature\ActionSequence;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceIndexUnauthenticatedTest
 * @package Tests\Feature
 */
class ActionSequenceIndexUnauthenticatedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $response = $this->json('GET', '/api/v1/action_sequences');
        $response->assertStatus(401);

    }

}
