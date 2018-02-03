<?php

namespace Tests\Feature\API\Pump;


use App\Pump;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class PumpUpdateUnauthorizedTest
 * @package Tests\Feature
 */
class PumpUpdateUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $pump = Pump::create([
            'name' => 'TestPump01'
        ]);

        $response = $this->put('/api/v1/pumps/' . $pump->id, [
            'name' => 'TestPump01_Updated'
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $pump->delete();

        $this->cleanupUsers();

    }

}
