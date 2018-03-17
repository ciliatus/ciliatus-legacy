<?php

namespace Tests\Feature\API\CustomComponentType;

use App\CustomComponentType;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class CustomComponentTypeUpdateUnauthorizedTest
 * @package Tests\Feature
 */
class CustomComponentTypeUpdateUnauthorizedTest extends TestCase
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

        $response = $this->put('/api/v1/custom_component_types/' . $custom_component_type->id, [
            'name_plural' => 'TestComponentTypes01_Updated'
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $this->cleanupUsers();

    }

}
