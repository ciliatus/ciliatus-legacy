<?php

namespace Tests\Feature\API\ActionSequenceTrigger;

use App\ActionSequenceTrigger;
use App\Terrarium;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;
use Webpatser\Uuid\Uuid;

/**
 * Class ActionSequenceTriggerStoreUnprocessableEntityTest
 * @package Tests\Feature
 */
class ActionSequenceTriggerStoreUnprocessableEntityTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserFullPermissions();

        $response = $this->json('POST', '/api/v1/action_sequence_triggers', [
            'name' => 'TestActionSequenceTrigger01',
            'action_sequence' => 'null'
        ], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(422);

        $this->cleanupUsers();

    }

}
