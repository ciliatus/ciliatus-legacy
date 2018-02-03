<?php

namespace Tests\Feature\API\Property;


use App\Animal;
use App\Property;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class PropertyUpdateUnauthorizedTest
 * @package Tests\Feature
 */
class PropertyUpdateUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

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
            'name' => 'TestProperty02'
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $property->delete();
        $animal->delete();

        $this->cleanupUsers();

    }

}
