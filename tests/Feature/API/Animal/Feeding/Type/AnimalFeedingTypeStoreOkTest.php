<?php

namespace Tests\Feature\API\AnimalFeedingType;

use App\Animal;
use App\AnimalFeedingType;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalFeedingTypeStoreOkTest
 * @package Tests\Feature
 */
class AnimalFeedingTypeStoreOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $response = $this->post('/api/v1/animals/feedings/types', [
            'name' => 'Food'
        ],
        [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $this->cleanupUsers();

    }

}
