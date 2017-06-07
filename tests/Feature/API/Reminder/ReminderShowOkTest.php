<?php

namespace Tests\Feature\Reminder;

use App\Animal;
use App\LogicalSensor;
use App\Reminder;
use App\PhysicalSensor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ReminderShowOkTest
 * @package Tests\Feature
 */
class ReminderShowOkTest extends TestCase
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

        // Reboot app to forget access token
        $this->app = $this->createApplication();
        $token = $this->createUserReadOnly();

        $response = $this->get('/api/v1/reminders/' . $id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $id,
                'name' => 'TestReminder01',
                'value' => 'Some Text'
            ]
        ]);

        $this->cleanupUsers();

    }

}
