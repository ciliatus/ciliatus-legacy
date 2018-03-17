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
 * Class CustomComponentDeleteOkTest
 * @package Tests\Feature
 */
class CustomComponentDeleteOkTest extends TestCase
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

        $response = $this->delete('/api/v1/custom_components/' . $custom_component->id, [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $this->assertNull(CustomComponent::find($custom_component->id));

        $this->cleanupUsers();
    }

}
