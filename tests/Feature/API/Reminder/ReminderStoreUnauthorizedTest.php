<?php

namespace Tests\Feature\Reminder;

use App\Animal;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ReminderStoreUnauthorizedTest
 * @package Tests\Feature
 */
class ReminderStoreUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $animal = Animal::create([
            'display_name' => 'TestAnimal01'
        ]);

        $response = $this->json('POST', '/api/v1/reminders', [
            'name' => 'TestReminder01',
            'value' => 'Some Text',
            'belongsTo_type' => 'Animal',
            'belongsTo_id' => $animal->id,
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $animal->delete();

        $this->cleanupUsers();

    }

}
