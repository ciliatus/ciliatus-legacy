<?php

namespace Tests\Feature\Valve;

use App\Animal;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class PropertyStoreUnauthorizedTest
 * @package Tests\Feature
 */
class PropertyStoreUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $animal = Animal::create([
            'display_name' => 'TestAnimal01'
        ]);

        $response = $this->json('POST', '/api/v1/properties', [
            'belongsTo_type' => 'Animal',
            'belongsTo_id' => $animal->id,
            'type' => 'TestProperty',
            'name' => 'TestProperty01'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $animal->delete();

        $this->cleanupUsers();

    }

}
