<?php

namespace App\Console\Commands;

use App\Animal;
use App\Repositories\AnimalFeedingScheduleRepository;
use App\Repositories\AnimalWeighingScheduleRepository;
use App\User;
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
        $is_weighings_due = false;
        $weighings_due = '';
        foreach (Animal::get() as $animal) {
            foreach ($animal->weighing_schedules as $afs) {
                $afs = (new AnimalWeighingScheduleRepository($afs))->show();
                if ($afs->next_weighing_at_diff <= 0) {
                    $is_weighings_due = true;
                    $weighings_due .= PHP_EOL . ' * ' . $animal->display_name;
                }
            }
        }

        $is_feedings_due = false;
        $feedings_due = '';
        foreach (Animal::get() as $animal) {
            foreach ($animal->feeding_schedules as $afs) {
                $afs = (new AnimalFeedingScheduleRepository($afs))->show();
                if ($afs->next_feeding_at_diff <= 0) {
                    $is_feedings_due = true;
                    $feedings_due .= PHP_EOL . ' * ' . $animal->display_name . ': ' . $afs->name;
                }
            }
        }

        if ($is_feedings_due || $is_weighings_due) {
            foreach (User::get() as $u) {

                if ($u->setting('notifications_enabled') == 'on'
                 && $u->setting('notifications_daily_enabled') == 'on') {

                    echo "Sending notifications to " . $u->email . PHP_EOL;

                    $text = trans('messages.daily.intro', [], $u->locale) . PHP_EOL;

                    if ($is_feedings_due) {
                        $text .= trans('messages.daily.feedings_due', [], $u->locale);
                        $text .= PHP_EOL . $feedings_due;
                    }

                    if ($is_weighings_due) {
                        $text .= trans('messages.daily.weighings_due', [], $u->locale);
                        $text .= PHP_EOL . $weighings_due;
                    }

                    foreach ($u->tokens()->where('revoked', false)->get() as $token) {

                        if (Carbon::now()->addDays(7)->gt($token->expires_at) &&
                            Carbon::now()->lt($token->expires_at)) {

                            $text .= trans('messages.own_token_expires', [
                                'name' => $token->name, 'days' => Carbon::now()->diffInDays($token->expires_at)
                            ], $u->locale);

                        }

                    }

                    $u->message($text);
                }

            }
        }

        \Log::debug('Notifications sent');
        return true;
    }
}
