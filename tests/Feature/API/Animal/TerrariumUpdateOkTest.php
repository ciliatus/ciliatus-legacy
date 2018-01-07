<?php

namespace Tests\Feature\API\Terrarium;

use App\Animal;
use App\Terrarium;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class TerrariumUpdateOkTest
 * @package Tests\Feature
 */
class TerrariumUpdateOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $terrarium = Terrarium::create([
            'name' => 'TestTerrarium01', 'display_name' => 'TestTerrarium01'
        ]);

        $animal1 = Animal::create(['display_name' => 'Animal01']);
        $animal2 = Animal::create(['display_name' => 'Animal02']);
        $animal3 = Animal::create(['display_name' => 'Animal03']);
        $valve1 = Valve::create(['name' => 'Valve01']);
        $valve2 = Valve::create(['name' => 'Valve02']);
        $valve3 = Valve::create(['name' => 'Valve03']);

        $response = $this->put('/api/v1/terraria/' . $terrarium->id, [
            'name' => 'TestTerrarium01_Updated',
            'valves' => [
                $valve1->id, $valve2->id
            ],
            'animals' => [
                $animal1->id, $animal2->id
            ]
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->put('/api/v1/terraria/' . $terrarium->id, [
            'valves' => [
                $valve3->id
            ],
            'animals' => [
                $animal3->id
            ]
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/terraria/' . $terrarium->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $terrarium->id,
                'name' => 'TestTerrarium01_Updated',
                'valves' => [
                    [
                        'id' => $valve3->id
                    ]
                ],
                'animals' => [
                    [
                        'id' => $animal3->id
                    ]
                ]
            ]
        ]);

        $terrarium->delete();
        $animal1->delete();
        $animal2->delete();
        $animal3->delete();
        $valve1->delete();
        $valve2->delete();
        $valve3->delete();

        $this->cleanupUsers();

    }

}
