<?php

namespace Tests\Feature\Web\Valve;

use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ValveIndexOkTest
 * @package Tests\Feature
 */
class ValveDeleteOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $obj = Valve::create(['display_name' => 'Valve01']);
        $this->actingAs($user)->get('/valves/' . $obj->id . '/delete')->assertStatus(200);
        $obj->delete();

        $this->cleanupUsers();

    }

}
