<?php

namespace Tests\Feature\API\ActionSequenceIntention;

use App\ActionSequenceIntention;
use App\Terrarium;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;
use Webpatser\Uuid\Uuid;

/**
 * Class ActionSequenceIntentionStoreUnprocessableEntityTest
 * @package Tests\Feature
 */
class ActionSequenceIntentionStoreUnprocessableEntityTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $response = $this->json('POST', '/api/v1/action_sequence_intentions', [
            'name' => 'TestActionSequenceIntention01',
            'action_sequence' => 'null'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $this->cleanupUsers();

    }

}
