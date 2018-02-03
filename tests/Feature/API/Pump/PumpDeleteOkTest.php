<?php

namespace Tests\Feature\API\Pump;

use App\Pump;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class PumpDeleteOkTest
 * @package Tests\Feature
 */
class PumpDeleteOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $pump = Pump::create([
            'name' => 'Pump01'
        ]);

        $response = $this->delete('/api/v1/pumps/' . $pump->id, [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $response = $this->get('/api/v1/pumps/' . $pump->id, [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(404);

        $this->cleanupUsers();

    }

}
