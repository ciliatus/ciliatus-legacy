<?php

namespace Tests\Feature\Web\Pump;

use App\Pump;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class PumpIndexOkTest
 * @package Tests\Feature
 */
class PumpShowOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $obj = Pump::create(['display_name' => 'Pump01']);
        $this->actingAs($user)->get('/pumps/' . $obj->id)->assertStatus(200);
        $obj->delete();

        $this->cleanupUsers();

    }

}
