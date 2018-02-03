<?php

namespace Tests\Feature\API\ActionSequenceSchedule;

use App\ActionSequenceSchedule;
use App\Terrarium;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;
use Webpatser\Uuid\Uuid;

/**
 * Class ActionSequenceScheduleStoreUnprocessableEntityTest
 * @package Tests\Feature
 */
class ActionSequenceScheduleStoreUnprocessableEntityTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $response = $this->json('POST', '/api/v1/action_sequence_schedules', [
            'name' => 'TestActionSequenceSchedule01',
            'action_sequence' => 'null'
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $this->cleanupUsers();

    }

}
