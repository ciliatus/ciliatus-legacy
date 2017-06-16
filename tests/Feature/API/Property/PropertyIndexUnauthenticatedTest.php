<?php

namespace Tests\Feature\Valve;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class PropertyIndexUnauthenticatedTest
 * @package Tests\Feature
 */
class PropertyIndexUnauthenticatedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $response = $this->json('GET', '/api/v1/properties');
        $response->assertStatus(401);

    }

}
