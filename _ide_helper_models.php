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
 * @property string $action_sequence_id
 * @property string $target_type
 * @property string $target_id
 * @property string $desired_state
 * @property int $duration_minutes
 * @property int $sequence_sort_id
 * @property string $wait_for_started_action_id
 * @property string $wait_for_finished_action_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\RunningAction[] $running_actions
 * @property-read \App\ActionSequence $sequence
 * @method static \Illuminate\Database\Query\Builder|\App\Action whereActionSequenceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Action whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Action whereDesiredState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Action whereDurationMinutes($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Action whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Action whereSequenceSortId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Action whereTargetId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Action whereTargetType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Action whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Action whereWaitForFinishedActionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Action whereWaitForStartedActionId($value)
 * @mixin \Eloquent
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
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property bool $runonce
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Action[] $actions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ActionSequenceIntention[] $intentions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ActionSequenceSchedule[] $schedules
 * @property-read \App\Terrarium $terrarium
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ActionSequenceTrigger[] $triggers
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequence whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequence whereDurationMinutes($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequence whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequence whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequence whereRunonce($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequence whereTerrariumId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequence whereUpdatedAt($value)
 * @mixin \Eloquent
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
 * @property int $minimum_timeout_minutes
 * @property string $timeframe_start
 * @property string $timeframe_end
 * @property \Carbon\Carbon $last_start_at
 * @property \Carbon\Carbon $last_finished_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \App\ActionSequence $sequence
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceIntention whereActionSequenceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceIntention whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceIntention whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceIntention whereIntention($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceIntention whereLastFinishedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceIntention whereLastStartAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceIntention whereMinimumTimeoutMinutes($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceIntention whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceIntention whereTimeframeEnd($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceIntention whereTimeframeStart($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceIntention whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceIntention whereUpdatedAt($value)
 * @mixin \Eloquent
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
 * @property \Carbon\Carbon $last_start_at
 * @property \Carbon\Carbon $last_finished_at
 * @property string $terrarium_id
 * @property bool $runonce
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $next_start_not_before
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \App\ActionSequence $sequence
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceSchedule whereActionSequenceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceSchedule whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceSchedule whereLastFinishedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceSchedule whereLastStartAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceSchedule whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceSchedule whereNextStartNotBefore($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceSchedule whereRunonce($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceSchedule whereStartsAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceSchedule whereTerrariumId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceSchedule whereUpdatedAt($value)
 * @mixin \Eloquent
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
 * @property int $minimum_timeout_minutes
 * @property string $timeframe_start
 * @property string $timeframe_end
 * @property \Carbon\Carbon $last_start_at
 * @property \Carbon\Carbon $last_finished_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\LogicalSensor $logical_sensor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \App\ActionSequence $sequence
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereActionSequenceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereLastFinishedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereLastStartAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereLogicalSensorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereMinimumTimeoutMinutes($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereReferenceValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereReferenceValueComparisonType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereReferenceValueDurationThresholdMinutes($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereTimeframeEnd($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereTimeframeStart($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ActionSequenceTrigger whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class ActionSequenceTrigger extends \Eloquent {}
}

namespace App{
/**
 * Class Animal
 *
 * @package App
 * @property string $id
 * @property string $terrarium_id
 * @property string $lat_name
 * @property string $common_name
 * @property string $display_name
 * @property string $gender
 * @property \Carbon\Carbon $birth_date
 * @property \Carbon\Carbon $death_date
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Event[] $biography_entries
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Event[] $caresheets
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Event[] $events
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \App\Terrarium $terrarium
 * @method static \Illuminate\Database\Query\Builder|\App\Animal whereBirthDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Animal whereCommonName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Animal whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Animal whereDeathDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Animal whereDisplayName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Animal whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Animal whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Animal whereLatName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Animal whereTerrariumId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Animal whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\AnimalFeedingEvent[] $feedings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\AnimalWeighingEvent[] $weighings
 */
	class Animal extends \Eloquent {}
}

namespace App{
/**
 * Class AnimalFeedingEvent
 *
 * @property string $id
 * @property string $belongsTo_type
 * @property string $belongsTo_id
 * @property string $type
 * @property string $name
 * @property string $value
 * @property string $value_json
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Query\Builder|\App\AnimalFeedingEvent whereBelongsToId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AnimalFeedingEvent whereBelongsToType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AnimalFeedingEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AnimalFeedingEvent whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AnimalFeedingEvent whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AnimalFeedingEvent whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AnimalFeedingEvent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AnimalFeedingEvent whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AnimalFeedingEvent whereValueJson($value)
 */
	class AnimalFeedingEvent extends \Eloquent {}
}

namespace App{
/**
 * Class AnimalWeighingEvent
 *
 * @property string $id
 * @property string $belongsTo_type
 * @property string $belongsTo_id
 * @property string $type
 * @property string $name
 * @property string $value
 * @property string $value_json
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Query\Builder|\App\AnimalWeighingEvent whereBelongsToId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AnimalWeighingEvent whereBelongsToType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AnimalWeighingEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AnimalWeighingEvent whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AnimalWeighingEvent whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AnimalWeighingEvent whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AnimalWeighingEvent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AnimalWeighingEvent whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\AnimalWeighingEvent whereValueJson($value)
 */
	class AnimalWeighingEvent extends \Eloquent {}
}

namespace App{
/**
 * Class BiographyEntryEvent
 *
 * @property string $id
 * @property string $belongsTo_type
 * @property string $belongsTo_id
 * @property string $type
 * @property string $name
 * @property string $value
 * @property string $value_json
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Query\Builder|\App\BiographyEntryEvent whereBelongsToId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BiographyEntryEvent whereBelongsToType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BiographyEntryEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BiographyEntryEvent whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BiographyEntryEvent whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BiographyEntryEvent whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BiographyEntryEvent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BiographyEntryEvent whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\BiographyEntryEvent whereValueJson($value)
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
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $software_version
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CriticalState[] $critical_states
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\GenericComponent[] $generic_components
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PhysicalSensor[] $physical_sensors
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Pump[] $pumps
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Valve[] $valves
 * @method static \Illuminate\Database\Query\Builder|\App\Controlunit whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Controlunit whereHeartbeatAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Controlunit whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Controlunit whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Controlunit whereSoftwareVersion($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Controlunit whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Controlunit extends \Eloquent {}
}

namespace App{
/**
 * Class CriticalState
 *
 * @package App
 * @property string $id
 * @property string $name
 * @property string $belongsTo_type
 * @property string $belongsTo_id
 * @property bool $is_soft_state
 * @property \Carbon\Carbon $notifications_sent_at
 * @property \Carbon\Carbon $recovered_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Query\Builder|\App\CriticalState whereBelongsToId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CriticalState whereBelongsToType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CriticalState whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CriticalState whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CriticalState whereIsSoftState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CriticalState whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CriticalState whereNotificationsSentAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CriticalState whereRecoveredAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CriticalState whereUpdatedAt($value)
 * @mixin \Eloquent
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
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereBelongsToId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereBelongsToType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Event whereValueJson($value)
 * @mixin \Eloquent
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
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $usage
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Query\Builder|\App\File whereBelongsToId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\File whereBelongsToType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\File whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\File whereDisplayName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\File whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\File whereMimetype($value)
 * @method static \Illuminate\Database\Query\Builder|\App\File whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\File whereParentPath($value)
 * @method static \Illuminate\Database\Query\Builder|\App\File whereSize($value)
 * @method static \Illuminate\Database\Query\Builder|\App\File whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\File whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\File whereUsage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\File whereUserId($value)
 * @mixin \Eloquent
 */
	class File extends \Eloquent {}
}

namespace App{
/**
 * Class GenericComponent
 *
 * @package App
 * @property string $id
 * @property string $belongsTo_type
 * @property string $belongsTo_id
 * @property string $controlunit_id
 * @property string $name
 * @property string $generic_component_type_id
 * @property string $state
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Controlunit $controlunit
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $states
 * @property-read \App\GenericComponentType $type
 * @method static \Illuminate\Database\Query\Builder|\App\GenericComponent whereBelongsToId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GenericComponent whereBelongsToType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GenericComponent whereControlunitId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GenericComponent whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GenericComponent whereGenericComponentTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GenericComponent whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GenericComponent whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GenericComponent whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GenericComponent whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class GenericComponent extends \Eloquent {}
}

namespace App{
/**
 * Class GenericComponentType
 *
 * @package App
 * @property string $id
 * @property string $name_singular
 * @property string $name_plural
 * @property string $icon
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $default_running_state_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\GenericComponent[] $components
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $intentions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $states
 * @method static \Illuminate\Database\Query\Builder|\App\GenericComponentType whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GenericComponentType whereDefaultRunningStateId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GenericComponentType whereIcon($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GenericComponentType whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GenericComponentType whereNamePlural($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GenericComponentType whereNameSingular($value)
 * @method static \Illuminate\Database\Query\Builder|\App\GenericComponentType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class GenericComponentType extends \Eloquent {}
}

namespace App{
/**
 * Class Log
 *
 * @package App
 * @property string $id
 * @property string $source_type
 * @property string $source_id
 * @property string $target_type
 * @property string $target_id
 * @property string $associatedWith_type
 * @property string $associatedWith_id
 * @property string $action
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $description
 * @property string $type
 * @property string $source_name
 * @property string $target_name
 * @property string $associatedWith_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Query\Builder|\App\Log whereAction($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Log whereAssociatedWithId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Log whereAssociatedWithName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Log whereAssociatedWithType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Log whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Log whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Log whereSourceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Log whereSourceName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Log whereSourceType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Log whereTargetId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Log whereTargetName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Log whereTargetType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Log whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Log whereUpdatedAt($value)
 * @mixin \Eloquent
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
 * @property float $rawvalue
 * @property float $rawvalue_lowerlimit
 * @property float $rawvalue_upperlimit
 * @property mixed $soft_state_duration_minutes
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CriticalState[] $critical_states
 * @property-read \App\PhysicalSensor $physical_sensor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Sensorreading[] $sensorreadings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LogicalSensorThreshold[] $thresholds
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensor whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensor whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensor whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensor wherePhysicalSensorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensor whereRawvalue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensor whereRawvalueLowerlimit($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensor whereRawvalueUpperlimit($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensor whereSoftStateDurationMinutes($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensor whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class LogicalSensor extends \Eloquent {}
}

namespace App{
/**
 * Class LogicalSensorThreshold
 *
 * @package App
 * @property string $id
 * @property string $name
 * @property string $logical_sensor_id
 * @property float $rawvalue_lowerlimit
 * @property float $rawvalue_upperlimit
 * @property string $starts_at
 * @property bool $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\LogicalSensor $logical_sensor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensorThreshold whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensorThreshold whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensorThreshold whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensorThreshold whereLogicalSensorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensorThreshold whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensorThreshold whereRawvalueLowerlimit($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensorThreshold whereRawvalueUpperlimit($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensorThreshold whereStartsAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LogicalSensorThreshold whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class LogicalSensorThreshold extends \Eloquent {}
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
 * @property string $model
 * @property \Carbon\Carbon $heartbeat_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Controlunit $controlunit
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LogicalSensor[] $logical_sensors
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \App\Terrarium $terrarium
 * @method static \Illuminate\Database\Query\Builder|\App\PhysicalSensor whereBelongsToId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PhysicalSensor whereBelongsToType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PhysicalSensor whereControlunitId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PhysicalSensor whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PhysicalSensor whereHeartbeatAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PhysicalSensor whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PhysicalSensor whereModel($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PhysicalSensor whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PhysicalSensor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class PhysicalSensor extends \Eloquent {}
}

namespace App{
/**
 * Class Property
 *
 * @package App
 * @property string $id
 * @property string $belongsTo_type
 * @property string $belongsTo_id
 * @property string $type
 * @property string $name
 * @property bool $value
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Query\Builder|\App\Property whereBelongsToId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Property whereBelongsToType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Property whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Property whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Property whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Property whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Property whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Property whereValue($value)
 * @mixin \Eloquent
 */
	class Property extends \Eloquent {}
}

namespace App{
/**
 * Class Pump
 *
 * @package App
 * @property string $id
 * @property string $controlunit_id
 * @property string $name
 * @property string $state
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Controlunit $controlunit
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Valve[] $valves
 * @method static \Illuminate\Database\Query\Builder|\App\Pump whereControlunitId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pump whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pump whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pump whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pump whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pump whereUpdatedAt($value)
 * @mixin \Eloquent
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
 * @property string $action_sequence_schedule_id
 * @property \Carbon\Carbon $started_at
 * @property \Carbon\Carbon $finished_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $action_sequence_trigger_id
 * @property string $action_sequence_intention_id
 * @property-read \App\Action $action
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Query\Builder|\App\RunningAction whereActionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RunningAction whereActionSequenceIntentionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RunningAction whereActionSequenceScheduleId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RunningAction whereActionSequenceTriggerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RunningAction whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RunningAction whereFinishedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RunningAction whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RunningAction whereStartedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\RunningAction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class RunningAction extends \Eloquent {}
}

namespace App{
/**
 * Class Sensorreading
 *
 * @package App
 * @property string $id
 * @property string $sensorreadinggroup_id
 * @property string $logical_sensor_id
 * @property float $rawvalue
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property bool $is_anomaly
 * @property-read \App\LogicalSensor $logical_sensor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @method static \Illuminate\Database\Query\Builder|\App\Sensorreading whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sensorreading whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sensorreading whereIsAnomaly($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sensorreading whereLogicalSensorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sensorreading whereRawvalue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sensorreading whereSensorreadinggroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Sensorreading whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Sensorreading extends \Eloquent {}
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
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\TelegramMessage whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TelegramMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TelegramMessage whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TelegramMessage whereResponseTo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TelegramMessage whereSendAfter($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TelegramMessage whereSentAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TelegramMessage whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TelegramMessage whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TelegramMessage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\TelegramMessage whereUserId($value)
 * @mixin \Eloquent
 */
	class TelegramMessage extends \Eloquent {}
}

namespace App{
/**
 * Class Terrarium
 *
 * @property mixed physical_sensors
 * @property mixed logical_sensors
 * @property mixed properties
 * @package App
 * @property string $id
 * @property string $name
 * @property string $display_name
 * @property bool $notifications_enabled
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property bool $humidity_critical
 * @property bool $temperature_critical
 * @property bool $heartbeat_critical
 * @property float $cooked_humidity_percent
 * @property float $cooked_temperature_celsius
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ActionSequence[] $action_sequences
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Animal[] $animals
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\GenericComponent[] $generic_components
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LogicalSensor[] $logical_sensors
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PhysicalSensor[] $physical_sensors
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Valve[] $valves
 * @method static \Illuminate\Database\Query\Builder|\App\Terrarium whereCookedHumidityPercent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Terrarium whereCookedTemperatureCelsius($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Terrarium whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Terrarium whereDisplayName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Terrarium whereHeartbeatCritical($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Terrarium whereHumidityCritical($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Terrarium whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Terrarium whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Terrarium whereNotificationsEnabled($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Terrarium whereTemperatureCritical($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Terrarium whereUpdatedAt($value)
 * @mixin \Eloquent
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
 * @property string $locale
 * @property string $timezone
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserAbility[] $abilities
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserSetting[] $settings
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereLocale($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereTimezone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
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
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\UserAbility whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserAbility whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserAbility whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserAbility whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserAbility whereUserId($value)
 * @mixin \Eloquent
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
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\UserSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserSetting whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserSetting whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserSetting whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\UserSetting whereValue($value)
 * @mixin \Eloquent
 */
	class UserSetting extends \Eloquent {}
}

namespace App{
/**
 * Class Valve
 *
 * @package App
 * @property string $id
 * @property string $controlunit_id
 * @property string $terrarium_id
 * @property string $pump_id
 * @property string $name
 * @property string $state
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Controlunit $controlunit
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Property[] $properties
 * @property-read \App\Pump $pump
 * @property-read \App\Terrarium $terrarium
 * @method static \Illuminate\Database\Query\Builder|\App\Valve whereControlunitId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Valve whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Valve whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Valve whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Valve wherePumpId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Valve whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Valve whereTerrariumId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Valve whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Valve extends \Eloquent {}
}

