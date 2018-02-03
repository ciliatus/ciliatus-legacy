<?php

namespace Tests\Feature\API\ActionSequenceSchedule;

use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceScheduleIndexUnauthorizedTest
 * @package Tests\Feature
 */
class ActionSequenceScheduleIndexUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserNothing();

        $response = $this->json('GET', '/api/v1/action_sequence_schedules', [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $this->cleanupUsers();

    }

}
