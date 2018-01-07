<?php

namespace Tests\Feature\API\Animal;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalIndexUnauthenticatedTest
 * @package Tests\Feature
 */
class AnimalIndexUnauthenticatedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $response = $this->json('GET', '/api/v1/animals');
        $response->assertStatus(401);

    }

}
