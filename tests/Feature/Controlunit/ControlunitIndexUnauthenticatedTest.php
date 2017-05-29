<?php

namespace Tests\Feature\Controlunit;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ControlunitIndexUnauthenticatedTest
 * @package Tests\Feature
 */
class ControlunitIndexUnauthenticatedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $response = $this->json('GET', '/api/v1/controlunits');
        $response->assertStatus(401);

    }

}
