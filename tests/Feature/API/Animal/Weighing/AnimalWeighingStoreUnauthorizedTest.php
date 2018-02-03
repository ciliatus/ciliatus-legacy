<?php

namespace Tests\Feature\API\AnimalWeighing;

use App\Animal;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalWeighingStoreUnauthorizedTest
 * @package Tests\Feature
 */
class AnimalWeighingStoreUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

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
        $response->assertStatus(401);

        $this->cleanupUsers();

    }

}
