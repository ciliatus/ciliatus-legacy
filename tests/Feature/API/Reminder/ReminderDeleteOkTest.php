<?php

namespace Tests\Feature\Reminder;

use App\Animal;
use App\ReminderEvent;
use App\PhysicalSensor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ReminderDeleteOkTest
 * @package Tests\Feature
 */
class ReminderDeleteOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

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
        $response->assertStatus(200);

        $id = $response->decodeResponseJson()['data']['id'];

        $response = $this->delete('/api/v1/reminders/' . $id, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/reminders/' . $id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(404);

        $this->cleanupUsers();
    }

}
