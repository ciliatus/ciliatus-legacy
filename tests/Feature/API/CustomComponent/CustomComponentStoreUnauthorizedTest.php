<?php

namespace Tests\Feature\API\CustomComponent;

use App\Controlunit;
use App\CustomComponentType;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class CustomComponentStoreUnauthorizedTest
 * @package Tests\Feature
 */
class CustomComponentStoreUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $custom_component_type = CustomComponentType::create([
            'name_singular' => 'TestComponentType01',
            'name_plural'   => 'TestComponentTypes01',
            'icon'          => 'close'
        ]);

        $controlunit = Controlunit::create([
            'name' => 'TestControlunit01'
        ]);

        $response = $this->json('POST', '/api/v1/custom_components', [
            'name' => 'TestCustomComponent01',
            'type' => 'TestComponentType01',
            'custom_component_type_id' => $custom_component_type->id,
            'controlunit_id' => $controlunit->id
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $custom_component_type->delete();
        $controlunit->delete();

        $this->cleanupUsers();

    }

}
