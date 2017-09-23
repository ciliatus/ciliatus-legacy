<?php

namespace Tests\Feature\API\GenericComponent;

use App\Controlunit;
use App\GenericComponentType;
use App\LogicalSensor;
use App\GenericComponent;
use App\PhysicalSensor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class GenericComponentStoreOkTest
 * @package Tests\Feature
 */
class GenericComponentStoreOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $generic_component_type = GenericComponentType::create([
            'name_singular' => 'TestComponentType01',
            'name_plural'   => 'TestComponentTypes01',
            'icon'          => 'close'
        ]);

        $controlunit = Controlunit::create([
            'name' => 'TestControlunit01'
        ]);

        $response = $this->json('POST', '/api/v1/generic_components', [
            'name' => 'TestGenericComponent01',
            'type_id' => $generic_component_type->id,
            'controlunit' => $controlunit->id
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

        GenericComponent::find($id)->delete();
        $generic_component_type->delete();
        $controlunit->delete();

        $this->cleanupUsers();

    }

}
