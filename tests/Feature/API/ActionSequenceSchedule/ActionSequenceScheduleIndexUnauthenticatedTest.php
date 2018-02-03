<?php

namespace Tests\Feature\API\ActionSequenceSchedule;

use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceScheduleIndexUnauthenticatedTest
 * @package Tests\Feature
 */
class ActionSequenceScheduleIndexUnauthenticatedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $response = $this->json('GET', '/api/v1/action_sequence_schedules');
        $response->assertStatus(401);

    }

}
