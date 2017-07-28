<?php

namespace Tests\Feature\API\BiographyEntry;

use App\Animal;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class BiographyEntryStoreUnauthorizedTest
 * @package Tests\Feature
 */
class BiographyEntryStoreUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $response = $this->json('POST', '/api/v1/biography_entries/categories', [
            'name' => 'TestBiographyEntryCategory01',
            'icon' => 'close'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $animal = Animal::create([
            'display_name' => 'TestAnimal01'
        ]);

        $response = $this->json('POST', '/api/v1/biography_entries', [
            'title' => 'TestBiographyEntry01',
            'text' => 'Some Text',
            'belongsTo_type' => 'Animal',
            'belongsTo_id' => $animal->id,
            'category' => 'TestBiographyEntryCategory01'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $animal->delete();

        $this->cleanupUsers();

    }

}
