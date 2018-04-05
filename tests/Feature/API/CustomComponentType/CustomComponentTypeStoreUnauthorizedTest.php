<?php

namespace Tests\Feature\API\CustomComponentType;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class CustomComponentTypeStoreUnauthorizedTest
 * @package Tests\Feature
 */
class CustomComponentTypeStoreUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $response = $this->json('POST', '/api/v1/custom_component_types', [
            'name_singular' => 'TestComponentType01',
            'name_plural'   => 'TestComponentTypes01',
            'icon'          => 'close'
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $this->cleanupUsers();

    }

}
