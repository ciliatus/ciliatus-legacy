<?php

namespace Tests\Feature;

use App\Controlunit;
use App\Http\Transformers\ControlunitTransformer;
use App\Log;
use App\Pump;
use App\User;
use Tests\CiliatusCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogTest extends CiliatusCase
{
    /**
     * Test unauthorized
     */
    public function test_000_Unauthenticated()
    {
        $response = $this->json('GET', '/api/v1/logs');
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_100_IndexError()
    {
        $response = $this->json('GET', '/api/v1/logs');
        $response->assertStatus(401);
    }

    /**
     *
     */
    public function test_200_IndexOk()
    {
        $token = $this->createUserReadOnly(false);

        $response = $this->json('GET', '/api/v1/logs', [], [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => []
        ]);

        $this->cleanupUsers(false);
    }

    /**
     *
     */
    public function test_500_ShowOk()
    {
        $token = $this->createUserReadOnly(false);

        $source = User::first();
        $target = Pump::create([
            'name' => 'TestPump01'
        ]);
        $assoc = Controlunit::create([
            'name' => 'TestControlunit01'
        ]);

        $log = Log::create([
            'source_type' => 'User',
            'source_id' => $source->id,
            'target_type' => 'Pump',
            'target_id' => $target->id,
            'associatedWith_type' => 'Controlunit',
            'associatedWith_id' => $assoc->id,
            'action' => 'create'
        ]);

        $response = $this->get('/api/v1/logs/' . $log->id, [
            'HTTP_Authorization' => 'Bearer ' . $token
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $log->id,
                'action' => 'create',
                'source' => [
                    'id' => $source->id,
                    'name' => $source->name
                ],
                'target' => [
                    'id' => $target->id,
                    'name' => $target->name
                ],
                'associated' => [
                    'id' => $assoc->id,
                    'name' => $assoc->name
                ]
            ]
        ]);
    }
}
