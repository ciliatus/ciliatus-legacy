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
class AnimalCreateOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();
        $animal = Animal::create(['display_name' => 'Animal01', 'common_name' => 'Animal', 'lat_name' => 'Animal']);
        $this->actingAs($user)->get('/animals/caresheets/create?preset[belongsTo_type]=Animal&preset[belongsTo_id]=' . $animal->id)->assertStatus(200);

        $animal->delete();

        $this->cleanupUsers();

    }

}
