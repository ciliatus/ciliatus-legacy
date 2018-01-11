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
 * Class AdminCategoriesUnauthorizedTest
 * @package Tests\Feature
 */
class AdminCategoriesUnauthorizedTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $user = $this->createUserWeb();

        $this->actingAs($user)->get('/categories')->assertStatus(401);

        $this->cleanupUsers();

    }

}
