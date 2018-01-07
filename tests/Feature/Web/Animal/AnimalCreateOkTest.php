<?php

namespace Tests\Feature\Web\Animal;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class AnimalIndexOkTest
 * @package Tests\Feature
 */
class AnimalCreateOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $this->actingAs($user)->get('/animals/create')->assertStatus(200);

        $this->cleanupUsers();

    }

}
