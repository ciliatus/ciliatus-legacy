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
class AnimalCaresheetShowOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $animal = Animal::create(['display_name' => 'Animal01', 'common_name' => 'Animal', 'lat_name' => 'Animal']);
        $caresheet = $animal->generate_caresheet();
        $this->actingAs($user)->get('/animals/' . $animal->id . '/caresheets/' . $caresheet->id)->assertStatus(200);

        $caresheet->delete();
        $animal->delete();

        $this->cleanupUsers();

    }

}
