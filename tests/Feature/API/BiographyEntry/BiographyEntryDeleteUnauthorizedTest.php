<?php

namespace Tests\Feature\API\BiographyEntry;

use App\LogicalSensor;
use App\BiographyEntry;
use App\PhysicalSensor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;
use Webpatser\Uuid\Uuid;

/**
 * Class BiographyEntryDeleteUnauthorizedTest
 * @package Tests\Feature
 */
class BiographyEntryDeleteUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $response = $this->delete('/api/v1/biography_entries/' . Uuid::generate()->string, [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $this->cleanupUsers();

    }

}
