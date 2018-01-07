<?php

namespace Tests\Feature\API\Animal;

use App\Animal;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalUpdateUnprocessableEntityTest
 * @package Tests\Feature
 */
class AnimalUpdateUnprocessableEntityTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $animal = Animal::create([
            'display_name' => 'TestAnimal01'
        ]);

        $response = $this->put('/api/v1/animals/' . $animal->id, [
            'display_name' => 'TestAnimal01_Updated',
            'terrarium' => 'doesnotexist',
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $animal->delete();

        $this->cleanupUsers();

    }

}
