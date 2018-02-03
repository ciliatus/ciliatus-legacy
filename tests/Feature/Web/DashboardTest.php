<?php

namespace Tests\Feature\Web\Terrarium;

use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class DashboardTest
 * @package Tests\Feature
 */
class DashboardTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $this->actingAs($user)->get('/')->assertStatus(200);

        $this->cleanupUsers();

    }

}
