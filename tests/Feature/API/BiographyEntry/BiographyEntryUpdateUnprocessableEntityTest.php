<?php

namespace Tests\Feature\BiographyEntry;

use App\Animal;
use App\BiographyEntry;
use App\BiographyEntryEvent;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class BiographyEntryUpdateUnprocessableEntityTest
 * @package Tests\Feature
 */
class BiographyEntryUpdateUnprocessableEntityTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $response = $this->json('POST', '/api/v1/biography_entries/categories', [
            'name' => 'TestBiographyEntryCategory04',
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
            'category' => 'TestBiographyEntryCategory04'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $id = $response->decodeResponseJson()['data']['id'];

        $response = $this->put('/api/v1/biography_entries/' . $id, [
            'category' => 'TestBiographyEntryCategoryDoesNotExist'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        BiographyEntryEvent::find($id)->delete();
        $animal->delete();

        $this->cleanupUsers();

    }

}
