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
 * Class GenericComponentTypeDeleteOkTest
 * @package Tests\Feature
 */
class GenericComponentTypeDeleteOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $generic_component_type = GenericComponentType::create([
            'name' => 'TestGenericComponentType01'
        ]);

        $response = $this->delete('/api/v1/generic_component_types/' . $generic_component_type->id, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $this->assertNull(GenericComponentType::find($generic_component_type->id));

        $this->cleanupUsers();
    }

}
