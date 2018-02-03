<?php

namespace Tests\Feature\API\AnimalWeighing;

use App\Animal;
use App\AnimalWeighing;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalWeighingStoreOkTest
 * @package Tests\Feature
 */
class AnimalWeighingStoreOkTest extends TestCase
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

        $this->cleanupUsers();

    }

}
