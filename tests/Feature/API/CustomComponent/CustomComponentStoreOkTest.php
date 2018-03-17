<?php

namespace Tests\Feature\API\CustomComponent;

use App\Controlunit;
use App\CustomComponentType;
use App\LogicalSensor;
use App\CustomComponent;
use App\PhysicalSensor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class CustomComponentStoreOkTest
 * @package Tests\Feature
 */
class CustomComponentStoreOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $custom_component_type = CustomComponentType::create([
            'name_singular' => 'TestComponentType01',
            'name_plural'   => 'TestComponentTypes01',
            'icon'          => 'close'
        ]);

        $controlunit = Controlunit::create([
            'name' => 'TestControlunit01'
        ]);

        $response = $this->json('POST', '/api/v1/custom_components', [
            'name' => 'TestCustomComponent01',
            'type_id' => $custom_component_type->id,
            'controlunit' => $controlunit->id
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

        CustomComponent::find($id)->delete();
        $custom_component_type->delete();
        $controlunit->delete();

        $this->cleanupUsers();

    }

}
