<?php

namespace App\Console\Commands;

use App\File;
use App\Log;
use App\Terrarium;
use Illuminate\Console\Command;

class Update17b extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ciliatus:update:v1.7';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the database to v1.7-beta';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo "Starting upgrade to v1.7-beta" . PHP_EOL;

        echo "Updating file display names ..." . PHP_EOL;
        foreach (File::get() as $file) {
            $file->display_name = str_replace('.', '_', $file->display_name);
            $file->save();
        }

        echo "Done!" . PHP_EOL;
        return true;
    }
}
