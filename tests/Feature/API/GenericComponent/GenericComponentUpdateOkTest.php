<?php

namespace Tests\Feature\API\GenericComponent;

use App\Controlunit;
use App\GenericComponentType;
use App\LogicalSensor;
use App\PhysicalSensor;
use App\Pump;
use App\Terrarium;
use App\GenericComponent;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use phpDocumentor\Reflection\DocBlock\Tags\Generic;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class GenericComponentUpdateOkTest
 * @package Tests\Feature
 */
class GenericComponentUpdateOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $generic_component_type = GenericComponentType::create([
            'name' => 'TestGenericComponentType01'
        ]);

        $generic_component = GenericComponent::create([
            'generic_component_type_id' => $generic_component_type->id,
            'name' => 'GenericComponent01'
        ]);

        $response = $this->put('/api/v1/generic_components/' . $generic_component->id, [
            'name' => 'GenericComponent01_Updated'
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $generic_component = GenericComponent::find($generic_component->id);
        $this->assertEquals('GenericComponent01_Updated', $generic_component->name);

        $generic_component->delete();
        $generic_component_type->delete();

        $this->cleanupUsers();

    }

}
