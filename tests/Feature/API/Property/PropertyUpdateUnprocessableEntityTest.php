<?php

namespace Tests\Feature\API\Property;

use App\Animal;
use App\Property;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;
use Webpatser\Uuid\Uuid;

/**
 * class PropertyUpdateUnprocessableEntityTest
 * @package Tests\Feature
 */
class PropertyUpdateUnprocessableEntityTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $animal = Animal::create([
            'display_name' => 'TestAnimal01'
        ]);

        $property = Property::create([
            'type' => 'TestProperty',
            'name' => 'TestProperty01',
            'value' => 'TestValue01',
            'belongsTo_type' => 'Animal',
            'belongsTo_id' => $animal->id
        ]);

        $response = $this->put('/api/v1/properties/' . $property->id, [
            'belongsTo_type' => 'Controlunit',
            'belongsTo_id' => Uuid::generate()->string
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $response = $this->put('/api/v1/properties/' . $property->id, [
            'belongsTo_type' => 'Gibtsnicht',
            'belongsTo_id' => Uuid::generate()->string
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $property->delete();
        $animal->delete();

        $this->cleanupUsers();

    }

}
