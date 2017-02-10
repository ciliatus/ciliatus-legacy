<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ciliatus:notifications:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends scheduled notifications.';

    /**
     * Execute the console command.
     *
     * @return boolean
     */
    public function handle()
    {
        \Log::debug('Notifications sent');
        return true;
    }
}
