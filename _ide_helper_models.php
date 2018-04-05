<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * Class Action
 *
 * @package App
 * @property string $id
 * @property string|null $action_sequence_id
 * @property string $target_type
 * @property string $target_id
 * @property string $desired_state
 * @property int $duration_minutes
 * @property int $sequence_sort_id
 * @property string|null $wait_for_started_action_id
 * @property string|null $wait_for_finished_action_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\RunningAction[] $running_actions
 * @property-read \App\ActionSequence|null $sequence
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action whereActionSequenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action whereDesiredState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action whereDurationMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action whereSequenceSortId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action whereTargetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action whereTargetType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action whereWaitForFinishedActionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action whereWaitForStartedActionId($value)
 */
	class Action extends \Eloquent {}
}

namespace App{
/**
 * Class ActionSequence
 *
 * @package App
 * @property string $id
 * @property string $name
 * @property string $terrarium_id
 * @property int $duration_minutes
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property bool $runonce
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Action[] $actions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ActionSequenceIntention[] $intentions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ActionSequenceSchedule[] $schedules
 * @property-read \App\Terrarium $terrarium
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ActionSequenceTrigger[] $triggers
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequence whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequence whereDurationMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequence whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequence whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequence whereRunonce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequence whereTerrariumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequence whereUpdatedAt($value)
 */
	class ActionSequence extends \Eloquent {}
}

namespace App{
/**
 * Class ActionSequenceIntention
 *
 * @package App
 * @property string $id
 * @property string $name
 * @property string $action_sequence_id
 * @property string $type
 * @property string $intention
 * @property int|null $minimum_timeout_minutes
 * @property string|null $timeframe_start
 * @property string|null $timeframe_end
 * @property \Carbon\Carbon|null $last_start_at
 * @property \Carbon\Carbon|null $last_finished_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $next_start_not_before
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \App\ActionSequence $sequence
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceIntention whereActionSequenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceIntention whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceIntention whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceIntention whereIntention($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceIntention whereLastFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceIntention whereLastStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceIntention whereMinimumTimeoutMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceIntention whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceIntention whereNextStartNotBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceIntention whereTimeframeEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceIntention whereTimeframeStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceIntention whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceIntention whereUpdatedAt($value)
 */
	class ActionSequenceIntention extends \Eloquent {}
}

namespace App{
/**
 * Class ActionSequenceSchedule
 *
 * @package App
 * @property string $id
 * @property string $name
 * @property string $action_sequence_id
 * @property string $starts_at
 * @property \Carbon\Carbon|null $last_start_at
 * @property \Carbon\Carbon|null $last_finished_at
 * @property string $terrarium_id
 * @property bool $runonce
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $next_start_not_before
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \App\ActionSequence $sequence
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceSchedule whereActionSequenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceSchedule whereLastFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceSchedule whereLastStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceSchedule whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceSchedule whereNextStartNotBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceSchedule whereRunonce($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceSchedule whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceSchedule whereTerrariumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceSchedule whereUpdatedAt($value)
 */
	class ActionSequenceSchedule extends \Eloquent {}
}

namespace App{
/**
 * Class ActionSequenceTrigger
 *
 * @package App
 * @property string $id
 * @property string $name
 * @property string $action_sequence_id
 * @property string $logical_sensor_id
 * @property float $reference_value
 * @property string $reference_value_comparison_type
 * @property int $reference_value_duration_threshold_minutes
 * @property int|null $minimum_timeout_minutes
 * @property string|null $timeframe_start
 * @property string|null $timeframe_end
 * @property \Carbon\Carbon|null $last_start_at
 * @property \Carbon\Carbon|null $last_finished_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $next_start_not_before
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \App\LogicalSensor $logical_sensor
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \App\ActionSequence $sequence
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceTrigger whereActionSequenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceTrigger whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceTrigger whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceTrigger whereLastFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceTrigger whereLastStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceTrigger whereLogicalSensorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceTrigger whereMinimumTimeoutMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceTrigger whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceTrigger whereNextStartNotBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceTrigger whereReferenceValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceTrigger whereReferenceValueComparisonType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceTrigger whereReferenceValueDurationThresholdMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceTrigger whereTimeframeEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceTrigger whereTimeframeStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ActionSequenceTrigger whereUpdatedAt($value)
 */
	class ActionSequenceTrigger extends \Eloquent {}
}

namespace App{
/**
 * Class Animal
 *
 * @package App
 * @property string $id
 * @property string|null $terrarium_id
 * @property string|null $lat_name
 * @property string|null $common_name
 * @property string|null $display_name
 * @property string|null $gender
 * @property \Carbon\Carbon|null $birth_date
 * @property \Carbon\Carbon|null $death_date
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Event[] $biography_entries
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Event[] $caresheets
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Event[] $events
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\AnimalFeedingScheduleProperty[] $feeding_schedules
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\AnimalFeedingEvent[] $feedings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \App\Terrarium|null $terrarium
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\AnimalWeighingScheduleProperty[] $weighing_schedules
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\AnimalWeighingEvent[] $weighings
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Animal whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Animal whereCommonName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Animal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Animal whereDeathDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Animal whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Animal whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Animal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Animal whereLatName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Animal whereTerrariumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Animal whereUpdatedAt($value)
 */
	class Animal extends \Eloquent {}
}

namespace App{
/**
 * Class AnimalFeedingEvent
 *
 * @package App
 * @property string $id
 * @property string $belongsTo_type
 * @property string $belongsTo_id
 * @property string $type
 * @property string $name
 * @property string $value
 * @property string $value_json
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingEvent whereBelongsToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingEvent whereBelongsToType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingEvent whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingEvent whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingEvent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingEvent whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingEvent whereValueJson($value)
 */
	class AnimalFeedingEvent extends \Eloquent {}
}

namespace App{
/**
 * Class AnimalFeedingScheduleProperty
 *
 * @package App
 * @property string $id
 * @property string|null $belongsTo_type
 * @property string|null $belongsTo_id
 * @property string|null $type
 * @property string $name
 * @property bool $value
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Animal|null $animal
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingScheduleProperty whereBelongsToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingScheduleProperty whereBelongsToType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingScheduleProperty whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingScheduleProperty whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingScheduleProperty whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingScheduleProperty whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingScheduleProperty whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalFeedingScheduleProperty whereValue($value)
 */
	class AnimalFeedingScheduleProperty extends \Eloquent {}
}

namespace App{
/**
 * Class AnimalWeighingEvent
 *
 * @package App
 * @property string $id
 * @property string $belongsTo_type
 * @property string $belongsTo_id
 * @property string $type
 * @property string $name
 * @property string $value
 * @property string $value_json
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingEvent whereBelongsToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingEvent whereBelongsToType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingEvent whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingEvent whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingEvent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingEvent whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingEvent whereValueJson($value)
 */
	class AnimalWeighingEvent extends \Eloquent {}
}

namespace App{
/**
 * Class AnimalWeighingScheduleProperty
 *
 * @package App
 * @property string $id
 * @property string|null $belongsTo_type
 * @property string|null $belongsTo_id
 * @property string|null $type
 * @property string $name
 * @property bool $value
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Animal|null $animal
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingScheduleProperty whereBelongsToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingScheduleProperty whereBelongsToType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingScheduleProperty whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingScheduleProperty whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingScheduleProperty whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingScheduleProperty whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingScheduleProperty whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AnimalWeighingScheduleProperty whereValue($value)
 */
	class AnimalWeighingScheduleProperty extends \Eloquent {}
}

namespace App{
/**
 * Class BiographyEntryEvent
 *
 * @package App
 * @property string $id
 * @property string $belongsTo_type
 * @property string $belongsTo_id
 * @property string $type
 * @property string $name
 * @property string $value
 * @property string $value_json
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BiographyEntryEvent whereBelongsToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BiographyEntryEvent whereBelongsToType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BiographyEntryEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BiographyEntryEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BiographyEntryEvent whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BiographyEntryEvent whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BiographyEntryEvent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BiographyEntryEvent whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BiographyEntryEvent whereValueJson($value)
 */
	class BiographyEntryEvent extends \Eloquent {}
}

namespace App{
/**
 * Class Controlunit
 *
 * @package App
 * @property string $id
 * @property string $name
 * @property \Carbon\Carbon $heartbeat_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $software_version
 * @property int|null $client_server_time_diff_seconds
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CriticalState[] $critical_states
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CustomComponent[] $custom_components
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PhysicalSensor[] $physical_sensors
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Pump[] $pumps
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Valve[] $valves
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controlunit whereClientServerTimeDiffSeconds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controlunit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controlunit whereHeartbeatAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controlunit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controlunit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controlunit whereSoftwareVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Controlunit whereUpdatedAt($value)
 */
	class Controlunit extends \Eloquent {}
}

namespace App{
/**
 * Class CriticalState
 *
 * @package App
 * @property string $id
 * @property string|null $name
 * @property string $belongsTo_type
 * @property string $belongsTo_id
 * @property bool $is_soft_state
 * @property \Carbon\Carbon|null $notifications_sent_at
 * @property \Carbon\Carbon|null $recovered_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $state_details
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CriticalState whereBelongsToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CriticalState whereBelongsToType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CriticalState whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CriticalState whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CriticalState whereIsSoftState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CriticalState whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CriticalState whereNotificationsSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CriticalState whereRecoveredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CriticalState whereStateDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CriticalState whereUpdatedAt($value)
 */
	class CriticalState extends \Eloquent {}
}

namespace App{
/**
 * Class Event
 *
 * @package App
 * @property string $id
 * @property string $belongsTo_type
 * @property string $belongsTo_id
 * @property string $type
 * @property string $name
 * @property string $value
 * @property string $value_json
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event whereBelongsToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event whereBelongsToType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Event whereValueJson($value)
 */
	class Event extends \Eloquent {}
}

namespace App{
/**
 * Class File
 *
 * @package App
 * @property string $id
 * @property string $belongsTo_type
 * @property string $belongsTo_id
 * @property string $user_id
 * @property string $state
 * @property string $mimetype
 * @property string $name
 * @property string $parent_path
 * @property string $display_name
 * @property float $size
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $usage
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereBelongsToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereBelongsToType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereMimetype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereParentPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereUsage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereUserId($value)
 */
	class File extends \Eloquent {}
}

namespace App{
/**
 * Class CustomComponent
 *
 * @package App
 * @property string $id
 * @property string $belongsTo_type
 * @property string $belongsTo_id
 * @property string|null $controlunit_id
 * @property string $name
 * @property string $custom_component_type_id
 * @property string $state
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $component_properties
 * @property-read \App\Controlunit|null $controlunit
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $states
 * @property-read \App\CustomComponentType $type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomComponent whereBelongsToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomComponent whereBelongsToType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomComponent whereControlunitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomComponent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomComponent whereCustomComponentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomComponent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomComponent whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomComponent whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomComponent whereUpdatedAt($value)
 */
	class CustomComponent extends \Eloquent {}
}

namespace App{
/**
 * Class CustomComponentType
 *
 * @package App
 * @property string $id
 * @property string $name_singular
 * @property string $name_plural
 * @property string $icon
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $default_running_state_id
 * @property string $default_intention_type
 * @property string $default_intention_intention
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CustomComponent[] $components
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $intentions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $states
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomComponentType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomComponentType whereDefaultIntentionIntention($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomComponentType whereDefaultIntentionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomComponentType whereDefaultRunningStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomComponentType whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomComponentType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomComponentType whereNamePlural($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomComponentType whereNameSingular($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CustomComponentType whereUpdatedAt($value)
 */
	class CustomComponentType extends \Eloquent {}
}

namespace App{
/**
 * Class Log
 *
 * @package App
 * @property string $id
 * @property string|null $source_type
 * @property string|null $source_id
 * @property string|null $target_type
 * @property string|null $target_id
 * @property string|null $associatedWith_type
 * @property string|null $associatedWith_id
 * @property string $action
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $description
 * @property string $type
 * @property string|null $source_name
 * @property string|null $target_name
 * @property string|null $associatedWith_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereAssociatedWithId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereAssociatedWithName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereAssociatedWithType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereSourceName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereSourceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereTargetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereTargetName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereTargetType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereUpdatedAt($value)
 */
	class Log extends \Eloquent {}
}

namespace App{
/**
 * Class LogicalSensor
 *
 * @package App
 * @property string $id
 * @property string $name
 * @property string $physical_sensor_id
 * @property string $type
 * @property float|null $rawvalue
 * @property float $rawvalue_lowerlimit
 * @property float $rawvalue_upperlimit
 * @property mixed $soft_state_duration_minutes
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CriticalState[] $critical_states
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\PhysicalSensor $physical_sensor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Sensorreading[] $sensorreadings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LogicalSensorThreshold[] $thresholds
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LogicalSensor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LogicalSensor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LogicalSensor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LogicalSensor wherePhysicalSensorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LogicalSensor whereRawvalue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LogicalSensor whereRawvalueLowerlimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LogicalSensor whereRawvalueUpperlimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LogicalSensor whereSoftStateDurationMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LogicalSensor whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LogicalSensor whereUpdatedAt($value)
 */
	class LogicalSensor extends \Eloquent {}
}

namespace App{
/**
 * Class LogicalSensorThreshold
 *
 * @package App
 * @property string $id
 * @property string|null $name
 * @property string $logical_sensor_id
 * @property float|null $rawvalue_lowerlimit
 * @property float|null $rawvalue_upperlimit
 * @property string $starts_at
 * @property bool $active
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \App\LogicalSensor $logical_sensor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LogicalSensorThreshold whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LogicalSensorThreshold whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LogicalSensorThreshold whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LogicalSensorThreshold whereLogicalSensorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LogicalSensorThreshold whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LogicalSensorThreshold whereRawvalueLowerlimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LogicalSensorThreshold whereRawvalueUpperlimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LogicalSensorThreshold whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LogicalSensorThreshold whereUpdatedAt($value)
 */
	class LogicalSensorThreshold extends \Eloquent {}
}

namespace App{
/**
 * App\LogRequest
 *
 * @mixin \Eloquent
 */
	class LogRequest extends \Eloquent {}
}

namespace App{
/**
 * Class Message
 *
 * @package App
 * @property string $id
 * @property string $user_id
 * @property string $type
 * @property string $state
 * @property string $content
 * @property string $response_to
 * @property \Carbon\Carbon $send_after
 * @property \Carbon\Carbon $sent_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereResponseTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereSendAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereUserId($value)
 */
	class Message extends \Eloquent {}
}

namespace App{
/**
 * Class PhysicalSensor
 *
 * @package App
 * @property string $id
 * @property string $controlunit_id
 * @property string $belongsTo_type
 * @property string $belongsTo_id
 * @property string $name
 * @property string|null $model
 * @property \Carbon\Carbon|null $heartbeat_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Controlunit $controlunit
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LogicalSensor[] $logical_sensors
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \App\Terrarium $terrarium
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PhysicalSensor whereBelongsToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PhysicalSensor whereBelongsToType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PhysicalSensor whereControlunitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PhysicalSensor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PhysicalSensor whereHeartbeatAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PhysicalSensor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PhysicalSensor whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PhysicalSensor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PhysicalSensor whereUpdatedAt($value)
 */
	class PhysicalSensor extends \Eloquent {}
}

namespace App{
/**
 * Class Property
 *
 * @package App
 * @property string $id
 * @property string|null $belongsTo_type
 * @property string|null $belongsTo_id
 * @property string|null $type
 * @property string $name
 * @property bool $value
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Property whereBelongsToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Property whereBelongsToType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Property whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Property whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Property whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Property whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Property whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Property whereValue($value)
 */
	class Property extends \Eloquent {}
}

namespace App{
/**
 * Class Pump
 *
 * @package App
 * @property string $id
 * @property string|null $controlunit_id
 * @property string $name
 * @property string $state
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $model
 * @property-read \App\Controlunit|null $controlunit
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Valve[] $valves
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pump whereControlunitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pump whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pump whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pump whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pump whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pump whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Pump whereUpdatedAt($value)
 */
	class Pump extends \Eloquent {}
}

namespace App{
/**
 * Class RunningAction
 *
 * @package App
 * @property string $id
 * @property string $action_id
 * @property string|null $action_sequence_schedule_id
 * @property string|null $action_sequence_trigger_id
 * @property \Carbon\Carbon|null $started_at
 * @property \Carbon\Carbon|null $finished_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $action_sequence_intention_id
 * @property-read \App\Action $action
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RunningAction whereActionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RunningAction whereActionSequenceIntentionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RunningAction whereActionSequenceScheduleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RunningAction whereActionSequenceTriggerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RunningAction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RunningAction whereFinishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RunningAction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RunningAction whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RunningAction whereUpdatedAt($value)
 */
	class RunningAction extends \Eloquent {}
}

namespace App{
/**
 * Class Sensorreading
 *
 * @package App
 * @property string $id
 * @property string|null $sensorreadinggroup_id
 * @property string $logical_sensor_id
 * @property float $rawvalue
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property bool $is_anomaly
 * @property string $read_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \App\LogicalSensor $logical_sensor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sensorreading whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sensorreading whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sensorreading whereIsAnomaly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sensorreading whereLogicalSensorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sensorreading whereRawvalue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sensorreading whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sensorreading whereSensorreadinggroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sensorreading whereUpdatedAt($value)
 */
	class Sensorreading extends \Eloquent {}
}

namespace App{
/**
 * Class SuggestionEvent
 *
 * @package App
 * @property string $id
 * @property string $belongsTo_type
 * @property string $belongsTo_id
 * @property string $type
 * @property string $name
 * @property string $value
 * @property string $value_json
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionEvent whereBelongsToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionEvent whereBelongsToType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionEvent whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionEvent whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionEvent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionEvent whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionEvent whereValueJson($value)
 */
	class SuggestionEvent extends \Eloquent {}
}

namespace App{
/**
 * Class SuggestionProperty
 *
 * @package App
 * @property string $id
 * @property string|null $belongsTo_type
 * @property string|null $belongsTo_id
 * @property string|null $type
 * @property string $name
 * @property bool $value
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionProperty whereBelongsToId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionProperty whereBelongsToType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionProperty whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionProperty whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionProperty whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionProperty whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionProperty whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SuggestionProperty whereValue($value)
 */
	class SuggestionProperty extends \Eloquent {}
}

namespace App{
/**
 * Class System
 *
 * @package App
 * @mixin \Eloquent
 */
	class System extends \Eloquent {}
}

namespace App{
/**
 * Class TelegramMessage
 *
 * @package App
 * @property string $id
 * @property string $user_id
 * @property string $type
 * @property string $state
 * @property string $content
 * @property string $response_to
 * @property \Carbon\Carbon $send_after
 * @property \Carbon\Carbon $sent_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramMessage whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramMessage whereResponseTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramMessage whereSendAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramMessage whereSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramMessage whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramMessage whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramMessage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TelegramMessage whereUserId($value)
 */
	class TelegramMessage extends \Eloquent {}
}

namespace App{
/**
 * Class Terrarium
 *
 * @package App
 * @property string $id
 * @property string $name
 * @property string $display_name
 * @property bool $notifications_enabled
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property bool $humidity_critical
 * @property bool $temperature_critical
 * @property bool $heartbeat_critical
 * @property float|null $cooked_humidity_percent
 * @property float|null $cooked_temperature_celsius
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ActionSequence[] $action_sequences
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Animal[] $animals
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CustomComponent[] $custom_components
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LogicalSensor[] $logical_sensors
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PhysicalSensor[] $physical_sensors
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Valve[] $valves
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Terrarium whereCookedHumidityPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Terrarium whereCookedTemperatureCelsius($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Terrarium whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Terrarium whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Terrarium whereHeartbeatCritical($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Terrarium whereHumidityCritical($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Terrarium whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Terrarium whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Terrarium whereNotificationsEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Terrarium whereTemperatureCritical($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Terrarium whereUpdatedAt($value)
 */
	class Terrarium extends \Eloquent {}
}

namespace App{
/**
 * Class Model
 *
 * @package App
 * @mixin \Eloquent
 */
	class Token extends \Eloquent {}
}

namespace App{
/**
 * Class User
 *
 * @package App
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property string|null $locale
 * @property string|null $timezone
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserAbility[] $abilities
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserSetting[] $settings
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * Class UserAbility
 *
 * @package App
 * @property string $id
 * @property string $user_id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserAbility whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserAbility whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserAbility whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserAbility whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserAbility whereUserId($value)
 */
	class UserAbility extends \Eloquent {}
}

namespace App{
/**
 * Class UserSetting
 *
 * @package App
 * @property string $id
 * @property string $user_id
 * @property string $name
 * @property string $value
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserSetting whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserSetting whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserSetting whereValue($value)
 */
	class UserSetting extends \Eloquent {}
}

namespace App{
/**
 * Class Valve
 *
 * @package App
 * @property string $id
 * @property string|null $controlunit_id
 * @property string|null $terrarium_id
 * @property string $pump_id
 * @property string $name
 * @property string $state
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $model
 * @property-read \App\Controlunit|null $controlunit
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \App\Pump $pump
 * @property-read \App\Terrarium|null $terrarium
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Valve whereControlunitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Valve whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Valve whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Valve whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Valve whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Valve wherePumpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Valve whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Valve whereTerrariumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Valve whereUpdatedAt($value)
 */
	class Valve extends \Eloquent {}
}

