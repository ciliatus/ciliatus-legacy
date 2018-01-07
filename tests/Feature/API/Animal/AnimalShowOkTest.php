<?php

namespace Tests\Feature\API\Animal;

use App\Animal;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalShowOkTest
 * @package Tests\Feature
 */
class AnimalShowOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $animal = Animal::create([
            'display_name' => 'TestAnimal01',
            'common_name' => 'Large Animal',
            'latin_name' => 'Animalus Maximus',
            'gender' => 'female'
        ]);

        $response = $this->get('/api/v1/animals/' . $animal->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $animal->id,
                'display_name' => $animal->display_name,
                'common_name' => $animal->common_name,
                'latin_name' => $animal->latin_name,
                'gender' => $animal->gender
            ]
        ]);

        $animal->delete();

        $this->cleanupUsers();

    }

}
