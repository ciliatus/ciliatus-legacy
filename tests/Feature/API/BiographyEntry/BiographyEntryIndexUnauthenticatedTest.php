<?php

namespace Tests\Feature\API\BiographyEntry;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class BiographyEntryIndexUnauthenticatedTest
 * @package Tests\Feature
 */
class BiographyEntryIndexUnauthenticatedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $response = $this->json('GET', '/api/v1/biography_entries');
        $response->assertStatus(401);

    }

}
