<?php

namespace Tests\Feature\Web\Animal;

use App\Animal;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class AnimalIndexOkTest
 * @package Tests\Feature
 */
class AnimalShowOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $obj = Animal::create(['display_name' => 'Animal01']);
        $this->actingAs($user)->get('/animals/' . $obj->id)->assertStatus(200);
        $obj->delete();

        $this->cleanupUsers();

    }

}
