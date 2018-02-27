<?php

namespace App\Console\Commands;

use App\Terrarium;
use Illuminate\Console\Command;

class Update14b extends UpdateCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ciliatus:update:v1.3tov1.4';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the database from v1.3-beta to v1.4-beta';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->init_update('v1.3-beta', 'v1.4-beta');

        echo "Generating default suggestion settings ..." . PHP_EOL;
        foreach (Terrarium::get() as $t) {
            $t->generateDefaultSuggestionSettings();
        }

        echo "Done!" . PHP_EOL;
        return true;
    }
}
