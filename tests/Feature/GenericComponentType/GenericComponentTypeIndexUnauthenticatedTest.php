<?php

namespace Tests\Feature\GenericComponentType;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class GenericComponentTypeIndexUnauthenticatedTest
 * @package Tests\Feature
 */
class GenericComponentTypeIndexUnauthenticatedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $response = $this->json('GET', '/api/v1/generic_component_types');
        $response->assertStatus(401);

    }

}
