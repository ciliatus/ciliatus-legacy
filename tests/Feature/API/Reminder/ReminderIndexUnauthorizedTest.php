<?php

namespace Tests\Feature\Reminder;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ReminderIndexUnauthorizedTest
 * @package Tests\Feature
 */
class ReminderIndexUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserNothing();

        $response = $this->json('GET', '/api/v1/reminders', [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $this->cleanupUsers();

    }

}
