<?php

namespace Tests\Feature\ActionSequence;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceIndexUnauthorizedTest
 * @package Tests\Feature
 */
class ActionSequenceIndexUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserNothing();

        $response = $this->json('GET', '/api/v1/action_sequences', [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $this->cleanupUsers();

    }

}
