<?php

namespace Tests\Feature\Valve;

use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ValveUpdateUnauthorizedTest
 * @package Tests\Feature
 */
class ValveUpdateUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $valve = Valve::create([
            'name' => 'TestValve01'
        ]);

        $response = $this->put('/api/v1/valves/' . $valve->id, [
            'name' => 'TestValve01_Updated'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $this->cleanupUsers();

    }

}
