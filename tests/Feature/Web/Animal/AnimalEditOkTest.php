<?php

namespace Tests\Feature\Web\Animal;

use App\Animal;
use App\AnimalSequence;
use App\Valve;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransanimals;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class AnimalIndexOkTest
 * @package Tests\Feature
 */
class AnimalEditOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $obj = Animal::create(['display_name' => 'Animal01']);
        $this->actingAs($user)->get('/animals/' . $obj->id . '/edit')->assertStatus(200);
        $obj->delete();

        $this->cleanupUsers();

    }

}
