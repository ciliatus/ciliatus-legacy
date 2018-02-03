<?php

namespace Tests\Feature\API\ActionSequenceIntention;

use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceIntentionIndexUnauthenticatedTest
 * @package Tests\Feature
 */
class ActionSequenceIntentionIndexUnauthenticatedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $response = $this->json('GET', '/api/v1/action_sequence_intentions');
        $response->assertStatus(401);

    }

}
