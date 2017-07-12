<?php

namespace Tests\Feature\Valve;

use App\Controlunit;
use App\Pump;
use App\Terrarium;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ValveUpdateOkTest
 * @package Tests\Feature
 */
class ValveUpdateOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $valve = Valve::create([
            'name' => 'TestValve01'
        ]);

        $terrarium = Terrarium::create([
            'name' => 'ValveTerrarium01',
            'display_name' => 'ValveTerrarium01'
        ]);
        $pump = Pump::create([
            'name' => 'ValvePump01'
        ]);
        $controlunit = Controlunit::create([
            'name' => 'ValveControlunit01'
        ]);

        $response = $this->put('/api/v1/valves/' . $valve->id, [
            'name' => 'TestValve01_Updated',
            'pump' => $pump->id,
            'terrarium' => $terrarium->id,
            'controlunit' => $controlunit->id
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/valves/' . $valve->id . '/?with[]=controlunit&with[]=pump&with[]=terrarium', [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $valve->id,
                'name' => 'TestValve01_Updated',
                'terrarium' => [
                    'id' => $terrarium->id
                ],
                'pump' => [
                    'id' => $pump->id
                ],
                'controlunit' => [
                    'id' => $controlunit->id
                ]
            ]
        ]);

        $valve->delete();
        $terrarium->delete();
        $pump->delete();
        $controlunit->delete();

        $this->cleanupUsers();

    }

}
