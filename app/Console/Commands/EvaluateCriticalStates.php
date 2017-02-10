<?php

namespace App\Console\Commands;

use App\CriticalState;
use Illuminate\Console\Command;

class EvaluateCriticalStates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ciliatus:critical_states:evaluate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Evaluates logical sensor values and creates/deletes or notifies critical states.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return CriticalState::evaluate();
    }
}
