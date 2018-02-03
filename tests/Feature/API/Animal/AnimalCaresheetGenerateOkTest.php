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
class AnimalCaresheetGenerateOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $animal = Animal::create([
            'display_name' => 'TestAnimal01',
            'common_name' => 'Large Animal',
            'latin_name' => 'Animalus Maximus',
            'gender' => 'female'
        ]);

        $response = $this->post('/api/v1/animals/caresheets', [
            'belongsTo' => 'Animal|' . $animal->id,
            'sensor_history_days' => 30,
            'data_history_days' => 180
        ],
        [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $animal->delete();

        $this->cleanupUsers();

    }

}
