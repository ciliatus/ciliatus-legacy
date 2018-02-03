<?php

namespace Tests\Feature\API\Animal;


use App\Animal;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalUpdateUnauthorizedTest
 * @package Tests\Feature
 */
class AnimalUpdateUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $animal = Animal::create([
            'display_name' => 'TestAnimal01'
        ]);

        $response = $this->put('/api/v1/animals/' . $animal->id, [
            'display_name' => 'TestAnimal01_Updated'
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $animal->delete();

        $this->cleanupUsers();

    }

}
