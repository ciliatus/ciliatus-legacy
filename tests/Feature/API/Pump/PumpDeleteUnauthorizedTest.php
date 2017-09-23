<?php

namespace Tests\Feature\API\Pump;

use App\Pump;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class PumpDeleteUnauthorizedTest
 * @package Tests\Feature
 */
class PumpDeleteUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $pump = Pump::create([
            'name' => 'Pump01'
        ]);

        $response = $this->delete('/api/v1/pumps/' . $pump->id, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $pump->delete();

        $this->cleanupUsers();

    }

}
