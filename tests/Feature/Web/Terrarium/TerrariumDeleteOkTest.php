<?php

namespace Tests\Feature\Web\Terrarium;

use App\Terrarium;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class TerrariumIndexOkTest
 * @package Tests\Feature
 */
class TerrariumDeleteOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $obj = Terrarium::create(['display_name' => 'Terrarium01']);
        $this->actingAs($user)->get('/terraria/' . $obj->id . '/delete')->assertStatus(200);
        $obj->delete();

        $this->cleanupUsers();

    }

}
