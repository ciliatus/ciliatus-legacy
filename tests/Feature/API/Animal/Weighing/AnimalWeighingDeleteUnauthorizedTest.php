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
 * class AnimalWeighingDeleteUnauthorizedTest
 * @package Tests\Feature
 */
class AnimalWeighingDeleteUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token_full = $this->createUserFullPermissions();

        $animal = Animal::create([
            'name' => 'TestAnimalWeighing01', 'display_name' => 'TestAnimalWeighing01'
        ]);

        $response = $this->post('/api/v1/animals/' . $animal->id . '/weighings', [
            'weight' => '50',
            'created_at' => '2018-02-02'
        ],
        [
            'Authorization' => 'Bearer ' . $token_full
        ]);
        $response->assertStatus(200);
        $this->cleanupUsers();

        $id = $response->decodeResponseJson()['data']['id'];

        $response = $this->delete('/api/v1/animals/' . $animal->id . '/weighings/' . $id, [], [
            'Authorization' => 'Bearer ' . $this->createUserReadOnly()
        ]);
        $response->assertStatus(401);

        $animal->delete();

        $this->cleanupUsers();

    }

}
