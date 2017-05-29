<?php

namespace Tests\Feature\GenericComponentType;

use App\LogicalSensor;
use App\GenericComponentType;
use App\PhysicalSensor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class GenericComponentTypeDeleteUnauthorizedTest
 * @package Tests\Feature
 */
class GenericComponentTypeDeleteUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $generic_component_type = GenericComponentType::create([
            'name' => 'TestGenericComponentType01'
        ]);

        $response = $this->delete('/api/v1/generic_component_types/' . $generic_component_type->id, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $generic_component_type->delete();

        $this->cleanupUsers();

    }

}
