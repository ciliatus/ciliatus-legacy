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
 * Class GenericComponentDeleteOkTest
 * @package Tests\Feature
 */
class GenericComponentDeleteOkTest extends TestCase
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

        $response = $this->delete('/api/v1/generic_components/' . $generic_component->id, [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $this->assertNull(GenericComponent::find($generic_component->id));

        $this->cleanupUsers();
    }

}
