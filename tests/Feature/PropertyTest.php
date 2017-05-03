<?php

namespace Tests\Feature;

use App\Animal;
use App\Controlunit;
use App\Property;
use App\Terrarium;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CiliatusCase;
use Webpatser\Uuid\Uuid;

/**
 * Class PropertyTest
 * @package Tests\Feature
 */
class PropertyTest extends CiliatusCase
{

    /**
     * Test unauthorized
     */
    public function test_000_Unauthenticated()
    {
        $response = $this->json('GET', '/api/v1/properties');
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_100_IndexError()
    {
        $response = $this->json('GET', '/api/v1/properties');
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_100_StoreError()
    {
        $token = $this->createUserReadOnly(false);

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

        $this->cleanupUsers();
    }

    /**
     *
     */
    public function test_200_IndexOk()
    {
        $token = $this->createUserReadOnly(false);

        $response = $this->json('GET', '/api/v1/properties', [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => []
        ]);

        $this->cleanupUsers(false);
    }

    /**
     *
     */
    public function test_200_StoreError()
    {
        $token = $this->createUserFullPermissions(false);

        $animal = Animal::create([
            'display_name' => 'TestAnimal01'
        ]);

        $response = $this->json('POST', '/api/v1/properties', [
            'belongsTo_type' => 'Gibtsnicht',
            'belongsTo_id' => Uuid::generate()->string,
            'type' => 'TestProperty',
            'name' => 'TestProperty01'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $response = $this->json('POST', '/api/v1/properties', [
            'belongsTo_type' => 'Animal',
            'belongsTo_id' => Uuid::generate()->string,
            'type' => 'TestProperty',
            'name' => 'TestProperty01'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);


        $response = $this->json('POST', '/api/v1/properties', [
            'type' => 'TestProperty',
            'name' => 'TestProperty01'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);


        $response = $this->json('POST', '/api/v1/properties', [
            'belongsTo_type' => 'Animal',
            'belongsTo_id' => $animal->id,
            'type' => 'TestProperty',
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);



        $response = $this->json('POST', '/api/v1/properties', [
            'belongsTo_type' => 'Animal',
            'belongsTo_id' => $animal->id,
            'name' => 'TestProperty01'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $this->cleanupUsers(false);

        $animal->delete();
    }

    /**
     *
     */
    public function test_200_StoreOk()
    {
        $token = $this->createUserFullPermissions(false);

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

        $this->cleanupUsers(false);
    }

    /**
     *
     */
    public function test_300_UpdateError()
    {
        $token = $this->createUserReadOnly(false);

        $property_id = Property::first()->id;

        $response = $this->put('/api/v1/properties/' . $property_id, [
            'name' => 'TestProperty02'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_400_UpdateError()
    {
        $token = $this->createUserFullPermissions(false);

        $property_id = Property::first()->id;

        $response = $this->put('/api/v1/properties/' . $property_id, [
            'belongsTo_type' => 'Controlunit',
            'belongsTo_id' => Uuid::generate()->string
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $response = $this->put('/api/v1/properties/' . $property_id, [
            'belongsTo_type' => 'Gibtsnicht',
            'belongsTo_id' => Uuid::generate()->string
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);
    }

    /**
     *
     */
    public function test_400_UpdateOk()
    {
        $token = $this->createUserFullPermissions(false);

        $controlunit = Controlunit::create([
            'name' => 'PropertyControlunit02'
        ]);

        $property = Property::create([
            'belongsTo_type' => 'Controlunit',
            'belongsTo_id' => $controlunit->id,
            'name' => 'TestProperty01',
            'type' => 'TestPropertyType01',
            'value' => 'SomeValue'
        ]);

        $response = $this->put('/api/v1/properties/' . $property->id, [
            'belongsTo_type' => 'Controlunit',
            'belongsTo_id' => $controlunit->id,
            'type' => 'AnotherPropertyType',
            'name' => 'TestProperty01_Updated',
            'value' => 'TestContent_Updated',
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/properties/' . $property->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $property->id,
                'type' => 'AnotherPropertyType',
                'name' => 'TestProperty01_Updated',
                'value' => 'TestContent_Updated',
                'belongsTo_type' => 'Controlunit',
                'belongsTo_id' => $controlunit->id
            ]
        ]);

    }

    /**
     *
     */
    public function test_500_ShowOk()
    {
        $token = $this->createUserReadOnly(false);

        $controlunit = Controlunit::create([
            'name' => 'PropertyControlunit02'
        ]);

        $property = Property::create([
            'belongsTo_type' => 'Controlunit',
            'belongsTo_id' => $controlunit->id,
            'name' => 'TestProperty01',
            'type' => 'TestPropertyType01',
            'value' => 'SomeValue'
        ]);

        $response = $this->get('/api/v1/properties/' . $property->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $property->id,
                'belongsTo_type' => 'Controlunit',
                'belongsTo_id' => $controlunit->id,
                'name' => 'TestProperty01',
                'type' => 'TestPropertyType01',
                'value' => 'SomeValue'
            ]
        ]);
    }

    /**
     *
     */
    public function test_500_DeleteError()
    {
        $token = $this->createUserReadOnly(false);

        $controlunit = Controlunit::create([
            'name' => 'PropertyControlunit02'
        ]);

        $property = Property::create([
            'belongsTo_type' => 'Controlunit',
            'belongsTo_id' => $controlunit->id,
            'name' => 'TestProperty01',
            'type' => 'TestPropertyType01'
        ]);

        $response = $this->delete('/api/v1/properties/' . $property->id, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_600_DeleteOk()
    {
        $token = $this->createUserFullPermissions(false);

        $controlunit = Controlunit::create([
            'name' => 'PropertyControlunit02'
        ]);

        $property = Property::create([
            'belongsTo_type' => 'Controlunit',
            'belongsTo_id' => $controlunit->id,
            'name' => 'TestProperty01',
            'type' => 'TestPropertyType01'
        ]);

        $response = $this->delete('/api/v1/properties/' . $property->id, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/properties/' . $property->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(404);

    }
}
