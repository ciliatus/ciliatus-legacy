<?php

namespace Tests\Feature\API\ActionSequenceIntention;

use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceIntentionIndexUnauthorizedTest
 * @package Tests\Feature
 */
class ActionSequenceIntentionIndexUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserNothing();

        $response = $this->json('GET', '/api/v1/action_sequence_intentions', [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $this->cleanupUsers();

    }

}
