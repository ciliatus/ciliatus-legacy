<?php

namespace Tests\Feature\API\Valve;

use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ValveDeleteOkTest
 * @package Tests\Feature
 */
class ValveDeleteOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $valve = Valve::create([
            'name' => 'TestValve01'
        ]);

        $response = $this->delete('/api/v1/valves/' . $valve->id, [], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);

        $this->assertNull(Valve::find($valve->id));

        $this->cleanupUsers();
    }

}
