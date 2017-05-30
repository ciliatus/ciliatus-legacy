<?php

namespace Tests\Feature\Valve;

use App\Controlunit;
use App\Log;
use App\Pump;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class LogShowOkTest
 * @package Tests\Feature
 */
class LogShowOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $token = $this->createUserReadOnly();

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

        $log->delete();
        $source->delete();
        $target->delete();
        $assoc->delete();

        $this->cleanupUsers();

    }

}
