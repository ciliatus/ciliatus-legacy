<?php

namespace Tests\Feature\API\AnimalWeighing;

use App\Animal;
use App\AnimalWeighing;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalWeighingDeleteOkTest
 * @package Tests\Feature
 */
class AnimalWeighingDeleteOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $animal = Animal::create([
            'name' => 'TestAnimalWeighing01', 'display_name' => 'TestAnimalWeighing01'
        ]);

        $response = $this->post('/api/v1/animals/' . $animal->id . '/weighings', [
            'weight' => '50',
            'created_at' => '2018-02-02'
        ],
        [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $id = $response->decodeResponseJson()['data']['id'];

        $response = $this->delete('/api/v1/animals/' . $animal->id, [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/animals/' . $animal->id, [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(404);

        $this->cleanupUsers();

    }

}
