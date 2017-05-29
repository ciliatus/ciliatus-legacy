<?php

namespace Tests\Feature\GenericComponent;

use App\Controlunit;
use App\GenericComponentType;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class GenericComponentStoreUnauthorizedTest
 * @package Tests\Feature
 */
class GenericComponentStoreUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $generic_component_type = GenericComponentType::create([
            'name_singular' => 'TestComponentType01',
            'name_plural'   => 'TestComponentTypes01',
            'icon'          => 'close'
        ]);

        $controlunit = Controlunit::create([
            'name' => 'TestControlunit01'
        ]);

        $response = $this->json('POST', '/api/v1/generic_components', [
            'name' => 'TestGenericComponent01',
            'type' => 'TestComponentType01',
            'generic_component_type_id' => $generic_component_type->id,
            'controlunit_id' => $controlunit->id
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $generic_component_type->delete();
        $controlunit->delete();

        $this->cleanupUsers();

    }

}
