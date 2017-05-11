<?php

namespace Tests\Feature;

use App\GenericComponentType;
use Tests\CiliatusCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GenericComponentTypeTest extends CiliatusCase
{
    /**
     * Test unauthorized
     */
    public function test_000_Unauthenticated()
    {
        $response = $this->json('GET', '/api/v1/generic_component_types');
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_100_IndexError()
    {
        $response = $this->json('GET', '/api/v1/generic_component_types');
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_100_StoreError()
    {
        $token = $this->createUserReadOnly(false);

        $response = $this->json('POST', '/api/v1/generic_component_types', [
            'name_singular' => 'TestComponentType01',
            'name_plural'   => 'TestComponentTypes01',
            'icon'          => 'close'
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

        $response = $this->json('GET', '/api/v1/generic_component_types', [], [
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
    public function test_200_StoreOk()
    {
        $token = $this->createUserFullPermissions(false);

        $response = $this->json('POST', '/api/v1/generic_component_types', [
            'name_singular' => 'TestComponentType01',
            'name_plural'   => 'TestComponentTypes01',
            'icon'          => 'close'
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

        $gct = GenericComponentType::find($id);
        $this->assertNotNull($gct);
        $this->assertEquals('TestComponentType01', $gct->name_singular);
        $this->assertEquals('TestComponentTypes01', $gct->name_plural);
        $this->assertEquals('close', $gct->icon);

        $this->cleanupUsers(false);
    }

    /**
     *
     */
    public function test_300_UpdateError()
    {
        $token = $this->createUserReadOnly(false);

        $generic_component_type = GenericComponentType::create([
            'name_singular' => 'TestComponentType01',
            'name_plural'   => 'TestComponentTypes01',
            'icon'          => 'close'
        ]);

        $response = $this->put('/api/v1/generic_component_types/' . $generic_component_type->id, [
            'name_plural' => 'TestComponentTypes01_Updated'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_400_UpdateOk()
    {
        $token = $this->createUserFullPermissions(false);

        $generic_component_type = GenericComponentType::create([
            'name_singular' => 'TestComponentType01',
            'name_plural'   => 'TestComponentTypes01',
            'icon'          => 'close'
        ]);

        $response = $this->put('/api/v1/generic_component_types/' . $generic_component_type->id, [
            'name_singular'=>'TestComponentType01_Updated',
            'name_plural' => 'TestComponentTypes01_Updated',
            'icon' => 'open',
            'state' => [
                'open', 'closed'
            ]
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $generic_component_type->fresh();
        $states = array_flip(array_column($generic_component_type->states()->get()->toArray(), 'name'));

        $this->assertArrayHasKey('open', $states);
        $this->assertArrayHasKey('closed', $states);

        $response = $this->put('/api/v1/generic_component_types/' . $generic_component_type->id, [
            'name_singular'=>'TestComponentType01_Updated',
            'name_plural' => 'TestComponentTypes01_Updated',
            'icon' => 'open',
            'state' => [
                'open', 'notopen'
            ]
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $generic_component_type->fresh();
        $states = array_flip(array_column($generic_component_type->states()->get()->toArray(), 'name'));

        $this->assertArrayHasKey('open', $states);
        $this->assertArrayHasKey('notopen', $states);
        $this->assertArrayNotHasKey('closed', $states);

    }

    /**
     *
     */
    public function test_500_DeleteError()
    {
        $token = $this->createUserReadOnly(false);

        $generic_component_type = GenericComponentType::create([
            'name' => 'TestGenericComponentType01'
        ]);

        $response = $this->delete('/api/v1/generic_component_types/' . $generic_component_type->id, [], [
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

        $generic_component_type = GenericComponentType::create([
            'name' => 'TestGenericComponentType01'
        ]);

        $response = $this->delete('/api/v1/generic_component_types/' . $generic_component_type->id, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $this->assertNull(GenericComponentType::find($generic_component_type->id));

    }
}
