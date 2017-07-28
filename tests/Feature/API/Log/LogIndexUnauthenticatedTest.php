<?php

namespace Tests\Feature\API\Log;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class LogIndexUnauthenticatedTest
 * @package Tests\Feature
 */
class LogIndexUnauthenticatedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $response = $this->json('GET', '/api/v1/logs');
        $response->assertStatus(401);

    }

}
