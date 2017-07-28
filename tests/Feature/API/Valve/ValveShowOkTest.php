<?php

namespace Tests\Feature\API\Valve;

use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ValveShowOkTest
 * @package Tests\Feature
 */
class ValveShowOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $valve = Valve::create([
            'name' => 'TestValve01'
        ]);

        $response = $this->get('/api/v1/valves/' . $valve->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $valve->id,
                'name' => $valve->name
            ]
        ]);

        $valve->delete();

        $this->cleanupUsers();

    }

}
