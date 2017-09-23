<?php

namespace Tests\Feature\API\BiographyEntry;

use App\Animal;
use App\BiographyEntryEvent;
use App\Controlunit;
use App\LogicalSensor;
use App\PhysicalSensor;
use App\Pump;
use App\Terrarium;
use App\BiographyEntry;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class BiographyEntryUpdateOkTest
 * @package Tests\Feature
 */
class BiographyEntryUpdateOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $response = $this->json('POST', '/api/v1/biography_entries/categories', [
            'name' => 'TestBiographyEntryCategory06',
            'icon' => 'close'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->json('POST', '/api/v1/biography_entries/categories', [
            'name' => 'TestBiographyEntryCategory07',
            'icon' => 'close'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $animal = Animal::create([
            'display_name' => 'TestAnimal01'
        ]);

        $response = $this->json('POST', '/api/v1/biography_entries', [
            'title' => 'TestBiographyEntry01',
            'text' => 'Some Text',
            'belongsTo_type' => 'Animal',
            'belongsTo_id' => $animal->id,
            'category' => 'TestBiographyEntryCategory06'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $id = $response->decodeResponseJson()['data']['id'];

        $response = $this->put('/api/v1/biography_entries/' . $id, [
            'title' => 'TestBiographyEntry01_Updated',
            'category' => 'TestBiographyEntryCategory07'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);

        $response->assertStatus(200);

        $response = $this->get('/api/v1/biography_entries/' . $id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $id,
                'title' => 'TestBiographyEntry01_Updated',
                'category' => [
                    'name' => 'TestBiographyEntryCategory07'
                ]
            ]
        ]);

        BiographyEntryEvent::find($id)->delete();
        $animal->delete();

        $this->cleanupUsers();

    }

}
