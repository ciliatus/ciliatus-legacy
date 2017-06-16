<?php

namespace Tests\Feature\Reminder;

use App\Reminder;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;
use Webpatser\Uuid\Uuid;

/**
 * Class ReminderUpdateUnauthorizedTest
 * @package Tests\Feature
 */
class ReminderUpdateUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $response = $this->put('/api/v1/reminders/' . Uuid::generate()->string, [
            'category' => 'TestReminderCategory03'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $this->cleanupUsers();

    }

}
