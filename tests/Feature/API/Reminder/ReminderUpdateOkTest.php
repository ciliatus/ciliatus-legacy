<?php

namespace Tests\Feature\Reminder;

use App\Animal;
use App\ReminderEvent;
use App\Controlunit;
use App\LogicalSensor;
use App\PhysicalSensor;
use App\Pump;
use App\Terrarium;
use App\Reminder;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ReminderUpdateOkTest
 * @package Tests\Feature
 */
class ReminderUpdateOkTest extends TestCase
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

        $response = $this->put('/api/v1/reminders/' . $id, [
            'value' => 'Some Other Text'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200);

        $response = $this->get('/api/v1/reminders/' . $id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $id,
                'value' => 'Some Other Text'
            ]
        ]);

        ReminderEvent::find($id)->delete();
        $animal->delete();

        $this->cleanupUsers();

    }

}
