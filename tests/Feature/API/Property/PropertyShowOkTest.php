<?php

namespace Tests\Feature\Valve;

use App\Controlunit;
use App\Property;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class PropertyShowOkTest
 * @package Tests\Feature
 */
class PropertyShowOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

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

        $response = $this->get('/api/v1/properties/' . $property->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $property->id,
                'belongsTo_type' => 'Controlunit',
                'belongsTo_id' => $controlunit->id,
                'name' => 'TestProperty01',
                'type' => 'TestPropertyType01',
                'value' => 'SomeValue'
            ]
        ]);

        $property->delete();
        $controlunit->delete();

        $this->cleanupUsers();

    }

}
