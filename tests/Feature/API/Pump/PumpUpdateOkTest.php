<?php

namespace Tests\Feature\Valve;

use App\Controlunit;
use App\Pump;
use App\Terrarium;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class PumpUpdateOkTest
 * @package Tests\Feature
 */
class PumpUpdateOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $pump = Pump::create([
            'name' => 'Pump01'
        ]);
        $controlunit = Controlunit::create([
            'name' => 'PumpControlunit01'
        ]);

        $response = $this->put('/api/v1/pumps/' . $pump->id, [
            'name' => 'TestPump01_Updated',
            'controlunit' => $controlunit->id
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/pumps/' . $pump->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $pump->id,
                'name' => 'TestPump01_Updated',
                'controlunit' => [
                    'id' => $controlunit->id
                ]
            ]
        ]);

        $pump->delete();
        $controlunit->delete();

        $this->cleanupUsers();

    }

}
