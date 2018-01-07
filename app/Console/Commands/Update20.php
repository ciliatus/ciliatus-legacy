<?php

namespace App\Console\Commands;

use App\File;
use Illuminate\Console\Command;

class Update20 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ciliatus:update:v2.0';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the database to v2.0';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo "Starting upgrade to v2.0" . PHP_EOL;

        echo "Updating file associations ..." . PHP_EOL;
        foreach (File::get() as $file) {
            if (is_null($file->belongsTo_type) ||
                !in_array($file->belongsTo_type, ['Terrarium', 'Animal'])) {
                continue;
            }
            $class = 'App\\' . $file->belongsTo_type;
            $object = $class::find($file->belongsTo_id);
            if (is_null($object)) {
                continue;
            }
            $object->files()->save($file);
            echo "Association created: " . $class . " " . $object->id . " <-> " . $file->id . PHP_EOL;
        }

        echo "Done!" . PHP_EOL;
        return true;
    }
}
