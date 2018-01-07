<?php

namespace Tests\Feature\Web\Controlunit;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ControlunitIndexOkTest
 * @package Tests\Feature
 */
class ControlunitCreateOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $this->actingAs($user)->get('/controlunits/create')->assertStatus(200);

        $this->cleanupUsers();

    }

}
