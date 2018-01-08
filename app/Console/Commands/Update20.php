<?php

namespace App\Console\Commands;

use App\File;
use App\Property;
use Illuminate\Console\Command;
use Mockery\Exception;

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

        echo "Updating background image properties ..." . PHP_EOL;
        foreach (Property::where('type', 'generic')->where('name', 'is_default_background')->get() as $prop) {
            $file = $prop->belongsTo_object();
            if (!is_null($file)) {
                if (is_null($file->belongsTo_type) ||
                    is_null($file->belongsTo_id)) {
                    $prop->delete();
                    continue;
                }

                try {
                    $class_name = 'App\\' . $file->belongsTo_type;
                    $obj = $class_name::find($file->belongsTo_id);
                }
                catch (\Exception $ex) {
                    $prop->delete();
                    continue;
                }

                if (is_null($obj)) {
                    $prop->delete();
                    continue;
                }

                $p = Property::create();
                $p->belongsTo_type = $file->belongsTo_type;
                $p->belongsTo_id = $obj->id;
                $p->type = 'generic';
                $p->name = 'background_file_id';
                $p->value = $file->id;
                $p->save();

                echo "Background set: " . $class_name . " " . $obj->id . " <-> " . $file->id . PHP_EOL;
            }
            $prop->delete();
        }

        echo "Done!" . PHP_EOL;
        return true;
    }
}
