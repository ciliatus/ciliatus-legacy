<?php

namespace Tests\Feature\CriticalState;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class CriticalStateIndexUnauthenticatedTest
 * @package Tests\Feature
 */
class CriticalStateIndexUnauthenticatedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $response = $this->json('GET', '/api/v1/critical_states');
        $response->assertStatus(401);

    }

}
