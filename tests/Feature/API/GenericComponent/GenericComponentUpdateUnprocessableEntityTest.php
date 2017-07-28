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
 * Class GenericComponentUpdateUnprocessableEntityTest
 * @package Tests\Feature
 */
class GenericComponentUpdateUnprocessableEntityTest extends TestCase
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
            'properties' => [
                'doesnotexist'
            ]
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $generic_component->delete();
        $generic_component_type->delete();

        $this->cleanupUsers();

    }

}
