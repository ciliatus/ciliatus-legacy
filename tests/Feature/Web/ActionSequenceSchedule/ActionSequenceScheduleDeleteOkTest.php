<?php

namespace Tests\Feature\Web\ActionSequenceSchedule;

use App\ActionSequenceSchedule;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionSequenceScheduleIndexOkTest
 * @package Tests\Feature
 */
class ActionSequenceScheduleDeleteOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $obj = ActionSequenceSchedule::create(['starts_at' => '08:00']);
        $this->actingAs($user)->get('/action_sequence_schedules/' . $obj->id . '/delete')->assertStatus(200);
        $obj->delete();

        $this->cleanupUsers();

    }

}
