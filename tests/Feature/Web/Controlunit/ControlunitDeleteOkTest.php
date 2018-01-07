<?php

namespace Tests\Feature\Web\Controlunit;

use App\Controlunit;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ControlunitIndexOkTest
 * @package Tests\Feature
 */
class ControlunitDeleteOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $obj = Controlunit::create(['display_name' => 'Controlunit01']);
        $this->actingAs($user)->get('/controlunits/' . $obj->id . '/delete')->assertStatus(200);
        $obj->delete();

        $this->cleanupUsers();

    }

}
