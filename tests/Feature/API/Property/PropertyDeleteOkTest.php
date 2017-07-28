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
 * class PropertyDeleteOkTest
 * @package Tests\Feature
 */
class PropertyDeleteOkTest extends TestCase
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
            'type' => 'TestPropertyType01'
        ]);

        $response = $this->delete('/api/v1/properties/' . $property->id, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/properties/' . $property->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(404);

        $controlunit->delete();

        $this->cleanupUsers();

    }

}
