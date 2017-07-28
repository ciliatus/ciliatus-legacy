<?php

namespace Tests\Feature\API\Property;

use App\Animal;
use App\Property;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class PropertyStoreOkTest
 * @package Tests\Feature
 */
class PropertyStoreOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $animal = Animal::create([
            'display_name' => 'TestAnimal01'
        ]);

        $response = $this->json('POST', '/api/v1/properties', [
            'belongsTo_type' => 'Animal',
            'belongsTo_id' => $animal->id,
            'type' => 'TestProperty',
            'name' => 'TestProperty01',
            'value' => 'SomeValue'
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

        $response = $this->get('/api/v1/properties/' . $id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $id,
                'belongsTo_type' => 'Animal',
                'belongsTo_id' => $animal->id,
                'type' => 'TestProperty',
                'name' => 'TestProperty01',
                'value' => 'SomeValue'
            ]
        ]);

        Property::find($id)->delete();
        $animal->delete();

        $this->cleanupUsers();

    }

}
