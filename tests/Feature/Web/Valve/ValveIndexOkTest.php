<?php

namespace Tests\Feature\Web\Valve;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ValveIndexOkTest
 * @package Tests\Feature
 */
class ValveIndexOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $this->actingAs($user)->get('/valves')->assertStatus(200);

        $this->cleanupUsers();

    }

}
