<?php

namespace Tests\Feature\API\CustomComponentType;

use App\LogicalSensor;
use App\CustomComponentType;
use App\PhysicalSensor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class CustomComponentTypeStoreOkTest
 * @package Tests\Feature
 */
class CustomComponentTypeStoreOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $response = $this->json('POST', '/api/v1/custom_component_types', [
            'name_singular' => 'TestComponentType01',
            'name_plural'   => 'TestComponentTypes01',
            'icon'          => 'close'
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id'
            ]
        ]);

        $id = $response->decodeResponseJson()['data']['id'];

        $gct = CustomComponentType::find($id);
        $this->assertNotNull($gct);
        $this->assertEquals('TestComponentType01', $gct->name_singular);
        $this->assertEquals('TestComponentTypes01', $gct->name_plural);
        $this->assertEquals('close', $gct->icon);

        $gct->delete();

        $this->cleanupUsers();

    }

}
