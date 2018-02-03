<?php

namespace Tests\Feature\API\BiographyEntry;

use App\BiographyEntry;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;
use Webpatser\Uuid\Uuid;

/**
 * Class BiographyEntryUpdateUnauthorizedTest
 * @package Tests\Feature
 */
class BiographyEntryUpdateUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

        $response = $this->put('/api/v1/biography_entries/' . Uuid::generate()->string, [
            'category' => 'TestBiographyEntryCategory03'
        ], [
            'Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(401);

        $this->cleanupUsers();

    }

}
