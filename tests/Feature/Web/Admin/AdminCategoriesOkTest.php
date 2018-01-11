<?php

namespace Tests\Feature\Web\ActionSequenceTrigger;

use App\ActionSequence;
use App\ActionSequenceTrigger;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * Class AdminCategoriesOkTest
 * @package Tests\Feature
 */
class AdminCategoriesOkTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserAdmin();

        $this->actingAs($user)->get('/categories')->assertStatus(200);

        $this->cleanupUsers();

    }

}
