<?php

namespace Tests\Feature\Pump;

use App\Pump;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class PumpShowOkTest
 * @package Tests\Feature
 */
class PumpShowOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $pump = Pump::create([
            'name' => 'TestPump01'
        ]);

        $response = $this->get('/api/v1/pumps/' . $pump->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $pump->id,
                'name' => $pump->name
            ]
        ]);

        $pump->delete();

        $this->cleanupUsers();

    }

}
