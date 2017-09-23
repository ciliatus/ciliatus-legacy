<?php

namespace Tests\Feature\API\Property;

use App\Controlunit;
use App\Property;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class PropertyDeleteUnauthorizedTest
 * @package Tests\Feature
 */
class PropertyDeleteUnauthorizedTest extends TestCase
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
            'type' => 'TestPropertyType01'
        ]);

        $response = $this->delete('/api/v1/properties/' . $property->id, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $property->delete();
        $controlunit->delete();

        $this->cleanupUsers();

    }

}
