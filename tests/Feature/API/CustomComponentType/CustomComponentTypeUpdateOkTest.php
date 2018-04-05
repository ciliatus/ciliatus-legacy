<?php

namespace Tests\Feature\API\CustomComponentType;

use App\Controlunit;
use App\LogicalSensor;
use App\PhysicalSensor;
use App\Pump;
use App\Terrarium;
use App\CustomComponentType;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class CustomComponentTypeUpdateOkTest
 * @package Tests\Feature
 */
class CustomComponentTypeUpdateOkTest extends TestCase
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

        $response = $this->put('/api/v1/custom_component_types/' . $custom_component_type->id, [
            'name_singular'=>'TestComponentType01_Updated',
            'name_plural' => 'TestComponentTypes01_Updated',
            'icon' => 'open',
            'state' => [
                'open', 'closed'
            ]
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $custom_component_type->fresh();
        $states = array_flip(array_column($custom_component_type->states()->get()->toArray(), 'name'));

        $this->assertArrayHasKey('open', $states);
        $this->assertArrayHasKey('closed', $states);

        $response = $this->put('/api/v1/custom_component_types/' . $custom_component_type->id, [
            'name_singular'=>'TestComponentType01_Updated',
            'name_plural' => 'TestComponentTypes01_Updated',
            'icon' => 'open',
            'state' => [
                'open', 'notopen'
            ]
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $custom_component_type->fresh();
        $states = array_flip(array_column($custom_component_type->states()->get()->toArray(), 'name'));

        $this->assertArrayHasKey('open', $states);
        $this->assertArrayHasKey('notopen', $states);
        $this->assertArrayNotHasKey('closed', $states);

        $custom_component_type->delete();

        $this->cleanupUsers();

    }

}
