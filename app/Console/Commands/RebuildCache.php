<?php

namespace App\Console\Commands;

use App\System;
use App\Terrarium;
use Illuminate\Console\Command;

class RebuildCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ciliatus:cache:rebuild';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild caches to accelerate page load times.';

    /**
     * Execute the console command.
     *
     * @return boolean
     */
    public function handle()
    {
        echo "Rebuilding terrarium caches ..." . PHP_EOL;
        Terrarium::rebuild_cache();
        System::rebuild_cache();
        echo "Done" . PHP_EOL;

        return true;
    }
}
