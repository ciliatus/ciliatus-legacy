<?php

namespace Tests\Feature\Web\Action;

use App\Action;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class ActionIndexOkTest
 * @package Tests\Feature
 */
class ActionShowOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $obj = Action::create();
        $this->actingAs($user)->get('/actions/' . $obj->id)->assertStatus(302);
        $obj->delete();

        $this->cleanupUsers();

    }

}
