<?php

namespace Tests\Feature\API\Property;

use App\Animal;
use App\Controlunit;
use App\Property;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class PropertyUpdateOkTest
 * @package Tests\Feature
 */
class PropertyUpdateOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $controlunit = Controlunit::create([
            'name' => 'PropertyControlunit02'
        ]);

        $property = Property::create([
            'belongsTo_type' => 'Controlunit',
            'belongsTo_id' => $controlunit->id,
            'name' => 'TestProperty01',
            'type' => 'TestPropertyType01',
            'value' => 'SomeValue'
        ]);

        $response = $this->put('/api/v1/properties/' . $property->id, [
            'belongsTo_type' => 'Controlunit',
            'belongsTo_id' => $controlunit->id,
            'type' => 'AnotherPropertyType',
            'name' => 'TestProperty01_Updated',
            'value' => 'TestContent_Updated',
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/properties/' . $property->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $property->id,
                'type' => 'AnotherPropertyType',
                'name' => 'TestProperty01_Updated',
                'value' => 'TestContent_Updated',
                'belongsTo_type' => 'Controlunit',
                'belongsTo_id' => $controlunit->id
            ]
        ]);

        $property->delete();
        $controlunit->delete();

        $this->cleanupUsers();

    }

}
