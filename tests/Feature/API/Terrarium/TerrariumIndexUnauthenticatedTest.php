<?php

namespace Tests\Feature\API\Terrarium;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class TerrariumIndexUnauthenticatedTest
 * @package Tests\Feature
 */
class TerrariumIndexUnauthenticatedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $response = $this->json('GET', '/api/v1/terraria');
        $response->assertStatus(401);

    }

}
