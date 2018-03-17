<?php

namespace Tests\Feature\API\Terrarium;

use App\CustomComponent;
use App\CustomComponentType;
use App\PhysicalSensor;
use App\Terrarium;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use phpDocumentor\Reflection\DocBlock\Tags\Generic;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class TerrariumInfrastructureOkTest
 * @package Tests\Feature
 */
class TerrariumInfrastructureOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $terrarium = Terrarium::create([
            'name' => 'TestTerrarium01', 'display_name' => 'TestTerrarium01'
        ]);

        $valve = Valve::create([
            'name' => 'V',
            'terrarium_id' => $terrarium->id
        ]);
        $ps = PhysicalSensor::create([
            'name' => 'V',
            'belongsTo_type' => 'Terrarium',
            'belongsTo_id' => $terrarium->id
        ]);
        $gt = CustomComponentType::create([
            'name_singular' => 'T',
            'name_plural' => 'T',
            'icon' => 'T'
        ]);
        $generic = CustomComponent::create([
            'belongsTo_type' => 'Terrarium',
            'belongsTo_id' => $terrarium->id,
            'custom_component_type_id' => $gt->id,
            'name' => 'Comp'
        ]);

        $response = $this->get('/api/v1/terraria/' . $terrarium->id . '/infrastructure', [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => []
        ]);

        $ps->delete();
        $valve->delete();
        $generic->delete();
        $gt->delete();
        $terrarium->delete();

        $this->cleanupUsers();

    }

}
