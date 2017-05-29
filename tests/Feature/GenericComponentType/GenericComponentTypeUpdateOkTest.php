<?php

namespace Tests\Feature\GenericComponentType;

use App\Controlunit;
use App\LogicalSensor;
use App\PhysicalSensor;
use App\Pump;
use App\Terrarium;
use App\GenericComponentType;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class GenericComponentTypeUpdateOkTest
 * @package Tests\Feature
 */
class GenericComponentTypeUpdateOkTest extends TestCase
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

        $generic_component_type->delete();

        $this->cleanupUsers();

    }

}
