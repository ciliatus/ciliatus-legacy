<?php

namespace Tests\Feature\ActionSequence;

use App\ActionSequence;
use App\Terrarium;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;
use Webpatser\Uuid\Uuid;

/**
 * Class ActionSequenceStoreUnprocessableEntityTest
 * @package Tests\Feature
 */
class ActionSequenceStoreUnprocessableEntityTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $response = $this->json('POST', '/api/v1/action_sequences', [
            'name' => 'TestActionSequence01',
            'template' => 'irrigation',
            'runonce' => true,
            'duration_minutes' => 1
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $response = $this->json('POST', '/api/v1/action_sequences', [
            'terrarium' => Uuid::generate()->string,
            'name' => 'TestActionSequence01',
            'template' => 'irrigation',
            'runonce' => true,
            'duration_minutes' => 1
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $this->cleanupUsers();

    }

}
