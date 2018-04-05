<?php

namespace Tests\Feature\API\CustomComponent;

use App\CustomComponent;
use App\CustomComponentType;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class CustomComponentUpdateUnauthorizedTest
 * @package Tests\Feature
 */
class CustomComponentUpdateUnauthorizedTest extends TestCase
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

        $response = $this->put('/api/v1/custom_components/' . $custom_component->id, [
            'name' => 'CustomComponent01_Updated'
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $custom_component->delete();
        $custom_component_type->delete();

        $this->cleanupUsers();

    }

}
