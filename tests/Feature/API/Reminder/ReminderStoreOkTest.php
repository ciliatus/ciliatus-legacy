<?php

namespace Tests\Feature\Reminder;

use App\Animal;
use App\ReminderEvent;
use App\LogicalSensor;
use App\Reminder;
use App\PhysicalSensor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ReminderStoreOkTest
 * @package Tests\Feature
 */
class ReminderStoreOkTest extends TestCase
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
        $response->assertJsonStructure([
            'data' => [
                'id'
            ]
        ]);

        $id = $response->decodeResponseJson()['data']['id'];

        $response = $this->get('/api/v1/reminders/' . $id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $id,
                'name' => 'TestReminder01'
            ]
        ]);

        ReminderEvent::find($id)->delete();
        $animal->delete();

        $this->cleanupUsers();

    }

}
