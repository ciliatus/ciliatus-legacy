<?php

namespace Tests\Feature\API\Animal;

use App\Animal;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalDeleteUnauthorizedTest
 * @package Tests\Feature
 */
class AnimalDeleteUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $animal = Animal::create([
            'name' => 'TestAnimal01', 'display_name' => 'TestAnimal01'
        ]);

        $response = $this->delete('/api/v1/animals/' . $animal->id, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $animal->delete();

        $this->cleanupUsers();

    }

}
