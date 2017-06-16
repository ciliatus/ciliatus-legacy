<?php

namespace Tests\Feature\Reminder;

use App\LogicalSensor;
use App\Reminder;
use App\PhysicalSensor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;
use Webpatser\Uuid\Uuid;

/**
 * Class ReminderDeleteUnauthorizedTest
 * @package Tests\Feature
 */
class ReminderDeleteUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $response = $this->delete('/api/v1/reminders/' . Uuid::generate()->string, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $this->cleanupUsers();

    }

}
