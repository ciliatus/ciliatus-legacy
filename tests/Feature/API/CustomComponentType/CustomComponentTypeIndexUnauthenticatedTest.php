<?php

namespace Tests\Feature\API\CustomComponentType;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class CustomComponentTypeIndexUnauthenticatedTest
 * @package Tests\Feature
 */
class CustomComponentTypeIndexUnauthenticatedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $response = $this->json('GET', '/api/v1/custom_component_types');
        $response->assertStatus(401);

    }

}
