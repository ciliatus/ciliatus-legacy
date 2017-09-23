<?php

namespace Tests\Feature\API\Valve;

use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ValveUpdateUnprocessableEntityTest
 * @package Tests\Feature
 */
class ValveUpdateUnprocessableEntityTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $valve = Valve::create([
            'name' => 'TestValve01'
        ]);

        $response = $this->put('/api/v1/valves/' . $valve->id, [
            'name' => 'TestValve01_Updated',
            'terrarium' => 'doesnotexist',
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $response = $this->put('/api/v1/valves/' . $valve->id, [
            'name' => 'TestValve01_Updated',
            'pump' => 'doesnotexist',
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $response = $this->put('/api/v1/valves/' . $valve->id, [
            'name' => 'TestValve01_Updated',
            'controlunit' => 'doesnotexist',
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $this->cleanupUsers();

    }

}
