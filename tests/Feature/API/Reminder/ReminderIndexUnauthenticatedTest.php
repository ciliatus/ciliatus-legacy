<?php

namespace Tests\Feature\Reminder;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ReminderIndexUnauthenticatedTest
 * @package Tests\Feature
 */
class ReminderIndexUnauthenticatedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $response = $this->json('GET', '/api/v1/reminders');
        $response->assertStatus(401);

    }

}
