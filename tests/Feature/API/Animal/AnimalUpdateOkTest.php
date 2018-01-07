<?php

namespace Tests\Feature\API\Animal;

use App\Animal;
use App\Terrarium;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalUpdateOkTest
 * @package Tests\Feature
 */
class AnimalUpdateOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $animal = Animal::create(['display_name' => 'TestAnimal01']);
        $terrarium = Terrarium::create(['name' => 'Terrarium01']);

        $response = $this->put('/api/v1/animals/' . $animal->id, [
            'display_name' => 'TestAnimal01_Updated',
            'common_name' => 'Large Animal',
            'latin_name' => 'Animalus Maximus',
            'gender' => 'female',
            'terrarium' => $terrarium->id
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/animals/' . $animal->id . '/?with[]=terrarium', [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $animal->id,
                'display_name' => 'TestAnimal01_Updated',
                'common_name' => 'Large Animal',
                'latin_name' => 'Animalus Maximus',
                'gender' => 'female',
                'terrarium' => [
                    'id' => $terrarium->id
                ]
            ]
        ]);

        $animal->delete();
        $terrarium->delete();

        $this->cleanupUsers();

    }

}
