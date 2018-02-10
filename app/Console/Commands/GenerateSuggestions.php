<?php

namespace App\Console\Commands;

use App\Event;
use App\Property;
use App\Repositories\AnimalFeedingScheduleRepository;
use App\Repositories\AnimalWeighingScheduleRepository;
use App\Terrarium;
use Illuminate\Console\Command;

class GenerateSuggestions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ciliatus:suggestions:generate
                            {--terrarium_id= : Define if you only want to generate suggestions for a certain Terrarium} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates suggestions';

    /**
     * Execute the console command.
     *
     * @return boolean
     */
    public function handle()
    {
        $total = 0;

        $terraria = [];
        if (is_null($this->option('terrarium_id'))) {
            $terraria = Terrarium::get();
        }
        else {
            $terraria[] = Terrarium::find($this->option('terrarium_id'));
            if (is_null($terraria[0])) {
                echo 'Terrarium not found';
                return false;
            }
        }

        foreach ($terraria as $t) {

            $suggestions = $t->getSuggestions()['critical_states'];

            $total += count($suggestions);

            foreach ($suggestions as $type=>$suggestion) {
                $e = Event::create([
                    'belongsTo_type' => 'Terrarium',
                    'belongsTo_id' => $t->id,
                    'type' => 'Suggestion',
                    'name' => $type,
                    'value' => $suggestion['hour']
                ]);

                Property::create([
                    'belongsTo_type' => 'Event',
                    'belongsTo_id' => $e->id,
                    'type' => 'SuggestionProperty',
                    'name' => 'violation_type',
                    'value' => $suggestion['violation_type']
                ]);
            }
        }
        \Log::info('Generated ' . $total . ' suggestions');

        return true;
    }
}
