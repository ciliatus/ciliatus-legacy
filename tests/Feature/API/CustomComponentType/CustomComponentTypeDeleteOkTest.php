<?php

namespace Tests\Feature\API\CustomComponentType;

use App\LogicalSensor;
use App\CustomComponentType;
use App\PhysicalSensor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class CustomComponentTypeDeleteOkTest
 * @package Tests\Feature
 */
class CustomComponentTypeDeleteOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $custom_component_type = CustomComponentType::create([
            'name' => 'TestCustomComponentType01'
        ]);

        $response = $this->delete('/api/v1/custom_component_types/' . $custom_component_type->id, [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $this->assertNull(CustomComponentType::find($custom_component_type->id));

        $this->cleanupUsers();
    }

}
