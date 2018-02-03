<?php

namespace Tests\Feature\API\ActionSequenceTrigger;

use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceTriggerIndexUnauthenticatedTest
 * @package Tests\Feature
 */
class ActionSequenceTriggerIndexUnauthenticatedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $response = $this->json('GET', '/api/v1/action_sequence_triggers');
        $response->assertStatus(401);

    }

}
