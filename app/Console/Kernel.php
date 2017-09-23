<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\EvaluateCriticalStates::class,
        Commands\RebuildCache::class,
        Commands\SendNotifications::class,
        //Commands\Update13b::class,
        //Commands\Update14b::class,
        Commands\Update17b::class,
        Commands\ConvertLangToJson::class,
        Commands\GenerateSuggestions::class,
        Commands\DetectSensorreadingAnomalies::class,
        Commands\GenerateCaresheets::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->command('ciliatus:caresheets:generate')
                 ->monthly();

        $schedule->command('ciliatus:suggestions:generate')
                 ->monthly();

        $schedule->command('ciliatus:notifications:send')
                 ->dailyAt(env('SCHEDULED_NOTIFICATION_SEND_TIME', '09:00:00'));

        $schedule->command('ciliatus:detect_sensorreading_anomalies', ['--history_minutes=1440'])
                 ->dailyAt('03:00:00');

        $schedule->command('ciliatus:critical_states:evaluate')
                 ->everyMinute();

        $schedule->command('ciliatus:cache:rebuild')
                 ->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
