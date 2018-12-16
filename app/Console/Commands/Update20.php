<?php

namespace App\Console\Commands;

use App\Action;
use App\ActionSequenceSchedule;
use App\File;
use App\Log;
use App\Property;
use App\Sensorreading;
use App\User;
use App\UserAbility;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Update20 extends UpdateCommand
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
        $this->init_update('v1.10-beta', 'v2.0');

        Artisan::call('down');

        echo "Transforming Generic Component Type Intentions ..." . PHP_EOL;
        foreach (Property::where('type', 'CustomComponentTypeIntention')->get() as $type) {
            $name = $type->name;
            $type->name = $type->value;
            $type->value = $name;
            $type->save();
        }

        echo "Running database migration ..." . PHP_EOL;
        Artisan::call('migrate');

        echo "Filling sensorreading adjusted_rawvalue, this may take awhile ..." . PHP_EOL;
        $max = Sensorreading::count();
        for ($offset = 0; $offset < $max; $offset+=10000) {
            foreach (Sensorreading::take(10000)->offset($offset)->get() as $sr) {
                $sr->rawvalue_adjustment = 0;
                $sr->adjusted_value = $sr->rawvalue;
                $sr->save();
            }

            echo sprintf("%s%s - %s/%s", round($offset / $max * 100, 0), '%', $offset, $max) . PHP_EOL;
        }

        echo sprintf("%s - %s/%s", '100%', $max, $max) . PHP_EOL;

        return;

        echo "Updating permissions ..." . PHP_EOL;
        foreach (User::get() as $user) {
            if ($user->hasAbility('grant_api-list:raw')) {
                $user->grantAbility('grant_api-list:all');
            }
            if ($user->hasAbility('grant_api-write:generic_component')) {
                $user->grantAbility('grant_api-list:custom_component');
            }
            if ($user->hasAbility('grant_api-write:generic_component_type')) {
                $user->grantAbility('grant_api-list:custom_component_type');
            }
        }
        UserAbility::where('name', 'grant_api-list:raw')->delete();

        echo "Updating action sequence schedules ..." . PHP_EOL;
        foreach (ActionSequenceSchedule::get() as $ass) {
            for ($i = 0; $i < 7; $i++) {
                $ass->setProperty('ActionSequenceScheduleProperty', $i, true);
            }
        }

        echo "Updating actions ..." . PHP_EOL;
        foreach (Action::get() as $a) {
            if ($a->target_type == 'GenericComponent') {
                $a->target_type = 'CustomComponent';
                $a->save();
            }
        }

        echo "Updating logs ..." . PHP_EOL;
        foreach (Log::get() as $l) {
            if ($l->source_type == 'GenericComponent') {
                $l->source_type = 'CustomComponent';
                $l->save();
            }
            if ($l->target_type == 'GenericComponent') {
                $l->target_type = 'CustomComponent';
                $l->save();
            }
            if ($l->associatedWith_type == 'GenericComponent') {
                $l->associatedWith_type = 'CustomComponent';
                $l->save();
            }
        }

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

        echo "Updating sensorreading timestamps ..." . PHP_EOL;

        $blocksize = Sensorreading::query()->count() / 10;
        $blocks_done = 0;
        $i = 0;

        foreach (Sensorreading::get() as $sr) {
            if ($i >= $blocksize) {
                $i = 0;
                $blocks_done++;
                echo $blocks_done*10 . "%" . PHP_EOL;
            }

            $sr->read_at = $sr->created_at;
            $sr->save();

            $i +=1;
        }

        Artisan::call('up');
        echo "Done!" . PHP_EOL;
        echo PHP_EOL;
        echo "Attention: Please verify all your custom component types!" . PHP_EOL;

        return true;
    }
}
