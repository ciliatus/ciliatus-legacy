<?php

namespace Tests\Feature\Pump;

use App\Pump;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class PumpUpdateUnprocessableEntityTest
 * @package Tests\Feature
 */
class PumpUpdateUnprocessableEntityTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $pump = Pump::create([
            'name' => 'TestPump01'
        ]);

        $response = $this->put('/api/v1/pumps/' . $pump->id, [
            'name' => 'TestPump01_Updated',
            'controlunit' => 'doesnotexist',
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $pump->delete();

        $this->cleanupUsers();

    }

}
