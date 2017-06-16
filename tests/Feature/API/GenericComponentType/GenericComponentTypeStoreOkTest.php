<?php

namespace Tests\Feature\GenericComponentType;

use App\LogicalSensor;
use App\GenericComponentType;
use App\PhysicalSensor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class GenericComponentTypeStoreOkTest
 * @package Tests\Feature
 */
class GenericComponentTypeStoreOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

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

        $gct->delete();

        $this->cleanupUsers();

    }

}
