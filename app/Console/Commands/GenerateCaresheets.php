<?php

namespace App\Console\Commands;

use App\Animal;
use Illuminate\Console\Command;

class GenerateCaresheets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ciliatus:caresheets:generate
                            {--animal_id= : Define if you want to only generate a caresheet for one animal} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates caresheets for all animals or the defined animal';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $options = [
            'include_terrarium_history' => true,
            'include_biography_entries' => true,
            'include_weight'            => true,
            'include_feedings'          => true,
            'sensor_history_days'       => env('DEFAULT_CARESHEET_SENSOR_HISTORY_DAYS', 30),
            'data_history_days'         => env('DEFAULT_CARESHEET_DATA_HISTORY_DAYS', 180)
        ];

        if (!is_null($this->option('animal_id'))) {
            $animal = Animal::findOrFail($this->option('animal_id'));
            echo 'Generating caresheet for ' . $animal->display_name . PHP_EOL;
            $animal->generate_caresheet($options);
        }
        else {
            foreach (Animal::get() as $animal) {
                echo 'Generating caresheet for ' . $animal->display_name . PHP_EOL;
                $animal->generate_caresheet($options);
            }
        }
    }
}
