<?php

namespace Tests\Feature\API\CustomComponent;

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
 * Class CustomComponentDeleteUnauthorizedTest
 * @package Tests\Feature
 */
class CustomComponentDeleteUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $custom_component_type = CustomComponentType::create([
            'name' => 'TestCustomComponentType01'
        ]);

        $custom_component = CustomComponent::create([
            'custom_component_type_id' => $custom_component_type->id,
            'name' => 'CustomComponent01'
        ]);

        $response = $this->delete('/api/v1/custom_components/' . $custom_component->id, [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $custom_component->delete();
        $custom_component_type->delete();

        $this->cleanupUsers();

    }

}
