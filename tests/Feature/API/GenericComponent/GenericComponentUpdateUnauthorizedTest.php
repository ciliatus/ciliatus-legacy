<?php

namespace Tests\Feature\API\GenericComponent;

use App\GenericComponent;
use App\GenericComponentType;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class GenericComponentUpdateUnauthorizedTest
 * @package Tests\Feature
 */
class GenericComponentUpdateUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

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
        $response->assertStatus(401);

        $generic_component->delete();
        $generic_component_type->delete();

        $this->cleanupUsers();

    }

}
