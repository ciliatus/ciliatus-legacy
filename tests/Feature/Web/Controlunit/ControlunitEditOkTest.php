<?php

namespace Tests\Feature\Web\Controlunit;

use App\Controlunit;
use App\ControlunitSequence;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTranscontrolunits;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ControlunitIndexOkTest
 * @package Tests\Feature
 */
class ControlunitEditOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $obj = Controlunit::create(['display_name' => 'Controlunit01']);
        $this->actingAs($user)->get('/controlunits/' . $obj->id . '/edit')->assertStatus(200);
        $obj->delete();

        $this->cleanupUsers();

    }

}
