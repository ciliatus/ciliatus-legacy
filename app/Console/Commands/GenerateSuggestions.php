<?php

namespace App\Console\Commands;

use App\Animal;
use App\Event;
use App\Repositories\AnimalFeedingScheduleRepository;
use App\Repositories\AnimalWeighingScheduleRepository;
use App\Terrarium;
use App\User;
use Illuminate\Console\Command;

class GenerateSuggestions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ciliatus:suggestions:generate';

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
        foreach (Terrarium::get() as $t) {

            $suggestions = $t->getSuggestions()['critical_states'];

            foreach ($suggestions as $type=>$suggestion) {
                Event::create([
                    'belongsTo_type' => 'Terrarium',
                    'belongsTo_id' => $t->id,
                    'type' => 'Suggestion',
                    'name' => $type,
                    'value' => key($suggestion)
                ]);
            }
        }
        return true;
    }
}
