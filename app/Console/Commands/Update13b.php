<?php

namespace App\Console\Commands;

use App\Log;
use Illuminate\Console\Command;

class Update13b extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ciliatus:update:v1.2tov1.3';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the database from v1.2-beta to v1.3-beta';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo "Starting upgrade from v1.2-beta to v1.3-beta" . PHP_EOL;
        echo "Updating Logs ..." . PHP_EOL;
        foreach (Log::get() as $l) {
            if (!is_null($l->source)) {
                $l->source_name = $l->source->name;
            }

            if (!is_null($l->target)) {
                $l->target_name = $l->target->name;
            }

            if (!is_null($l->associated)) {
                $l->associatedWith_name = $l->associated->name;
            }

            $l->save();
        }

        echo "Done!";
        return true;
    }
}
