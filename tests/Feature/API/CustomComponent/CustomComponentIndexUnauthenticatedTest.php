<?php

namespace Tests\Feature\API\CustomComponent;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class CustomComponentIndexUnauthenticatedTest
 * @package Tests\Feature
 */
class CustomComponentIndexUnauthenticatedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $response = $this->json('GET', '/api/v1/custom_components');
        $response->assertStatus(401);

    }

}
