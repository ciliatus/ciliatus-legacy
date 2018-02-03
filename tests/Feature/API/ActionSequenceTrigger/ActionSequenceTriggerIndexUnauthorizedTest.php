<?php

namespace Tests\Feature\API\ActionSequenceTrigger;

use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceTriggerIndexUnauthorizedTest
 * @package Tests\Feature
 */
class ActionSequenceTriggerIndexUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserNothing();

        $response = $this->json('GET', '/api/v1/action_sequence_triggers', [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $this->cleanupUsers();

    }

}
