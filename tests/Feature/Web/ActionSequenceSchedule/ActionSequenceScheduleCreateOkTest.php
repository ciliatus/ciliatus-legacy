<?php

namespace Tests\Feature\Web\ActionSequenceSchedule;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceScheduleIndexOkTest
 * @package Tests\Feature
 */
class ActionSequenceScheduleCreateOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $this->actingAs($user)->get('/action_sequence_schedules/create')->assertStatus(200);

        $this->cleanupUsers();

    }

}
