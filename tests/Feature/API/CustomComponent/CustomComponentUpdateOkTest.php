<?php

namespace Tests\Feature\API\CustomComponent;

use App\Controlunit;
use App\CustomComponentType;
use App\LogicalSensor;
use App\PhysicalSensor;
use App\Pump;
use App\Terrarium;
use App\CustomComponent;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use phpDocumentor\Reflection\DocBlock\Tags\Generic;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class CustomComponentUpdateOkTest
 * @package Tests\Feature
 */
class CustomComponentUpdateOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $custom_component_type = CustomComponentType::create([
            'name' => 'TestCustomComponentType01'
        ]);

        $custom_component = CustomComponent::create([
            'custom_component_type_id' => $custom_component_type->id,
            'name' => 'CustomComponent01'
        ]);

        $response = $this->put('/api/v1/custom_components/' . $custom_component->id, [
            'name' => 'CustomComponent01_Updated'
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $custom_component = CustomComponent::find($custom_component->id);
        $this->assertEquals('CustomComponent01_Updated', $custom_component->name);

        $custom_component->delete();
        $custom_component_type->delete();

        $this->cleanupUsers();

    }

}
