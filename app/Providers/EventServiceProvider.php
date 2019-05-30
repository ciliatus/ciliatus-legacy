<?php

namespace App\Providers;

use App\Action;
use App\ActionSequence;
use App\ActionSequenceIntention;
use App\ActionSequenceSchedule;
use App\ActionSequenceTrigger;
use App\Animal;
use App\Controlunit;
use App\File;
use App\CustomComponent;
use App\CustomComponentType;
use App\LogicalSensor;
use App\Observers\ActionObserver;
use App\Observers\ActionSequenceIntentionObserver;
use App\Observers\ActionSequenceObserver;
use App\Observers\ActionSequenceScheduleObserver;
use App\Observers\ActionSequenceTriggerObserver;
use App\Observers\AnimalObserver;
use App\Observers\ControlunitObserver;
use App\Observers\FileObserver;
use App\Observers\CustomComponentObserver;
use App\Observers\CustomComponentTypeObserver;
use App\Observers\LogicalSensorObserver;
use App\Observers\PhysicalSensorObserver;
use App\Observers\PumpObserver;
use App\Observers\RoomObserver;
use App\Observers\SensorreadingObserver;
use App\Observers\TerrariumObserver;
use App\Observers\UserAbilityObserver;
use App\Observers\UserObserver;
use App\PhysicalSensor;
use App\Pump;
use App\Room;
use App\Sensorreading;
use App\Terrarium;
use App\User;
use App\UserAbility;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Action::observe(ActionObserver::class);
        ActionSequence::observe(ActionSequenceObserver::class);
        ActionSequenceIntention::observe(ActionSequenceIntentionObserver::class);
        ActionSequenceSchedule::observe(ActionSequenceScheduleObserver::class);
        ActionSequenceTrigger::observe(ActionSequenceTriggerObserver::class);
        Animal::observe(AnimalObserver::class);
        Controlunit::observe(ControlunitObserver::class);
        File::observe(FileObserver::class);
        CustomComponent::observe(CustomComponentObserver::class);
        CustomComponentType::observe(CustomComponentTypeObserver::class);
        LogicalSensor::observe(LogicalSensorObserver::class);
        PhysicalSensor::observe(PhysicalSensorObserver::class);
        Pump::observe(PumpObserver::class);
        Sensorreading::observe(SensorreadingObserver::class);
        Terrarium::observe(TerrariumObserver::class);
        Room::observe(RoomObserver::class);
        UserAbility::observe(UserAbilityObserver::class);
        User::observe(UserObserver::class);
    }
}
