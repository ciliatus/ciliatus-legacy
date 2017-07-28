<?php

namespace Tests\Feature\API\GenericComponent;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class GenericComponentIndexUnauthenticatedTest
 * @package Tests\Feature
 */
class GenericComponentIndexUnauthenticatedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $response = $this->json('GET', '/api/v1/generic_components');
        $response->assertStatus(401);

    }

}
