<?php

namespace Tests\Feature\API\Animal;

use Artisan;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\TestHelperTrait;

/**
 * class AnimalShowOkTest
 * @package Tests\Feature
 */
class ArtisanCommandsTest extends TestCase
{

    use TestHelperTrait;

    public function test()
    {

        $this->assertEquals(0, Artisan::call('ciliatus:cache:rebuild'));
        $this->assertEquals(0, Artisan::call('ciliatus:caresheets:generate'));
        $this->assertEquals(0, Artisan::call('ciliatus:convert_lang_to_json'));
        $this->assertEquals(0, Artisan::call('ciliatus:critical_states:evaluate'));
        $this->assertEquals(0, Artisan::call('ciliatus:detect_sensorreading_anomalies'));
        $this->assertEquals(0, Artisan::call('ciliatus:notifications:send'));
        $this->assertEquals(0, Artisan::call('ciliatus:suggestions:generate'));

    }

}
