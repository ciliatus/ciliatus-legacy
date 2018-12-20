<?php

namespace App;

use App\Traits\Uuids;
use Carbon\Carbon;

/**
 * Class ActionSequence
 * @package App
 */
class ActionSequence extends CiliatusModel
{

    use Uuids;

    const TEMPLATE_IRRIGATION = 'irrigate';
    const TEMPLATE_VENTILATE = 'ventilate';
    const TEMPLATE_HEAT_UP = 'heat_up';
    const TEMPLATE_COOL_DOWN = 'cool_down';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['name', 'terrarium_id', 'duration_minutes'];

    /**
     * @var array
     */
    protected $casts = [
        'runonce' => 'boolean'
    ];

    /**
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        $this->triggers()->delete();
        $this->schedules()->delete();
        $this->intentions()->delete();
        $this->actions()->delete();
        $this->properties()->delete();
        return parent::delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'ActionSequence');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function actions()
    {
        return $this->hasMany('App\Action')->orderBy('sequence_sort_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schedules()
    {
        return $this->hasMany('App\ActionSequenceSchedule')->orderBy('starts_at');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function triggers()
    {
        return $this->hasMany('App\ActionSequenceTrigger')->with('logical_sensor')->orderBy('timeframe_start');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function intentions()
    {
        return $this->hasMany('App\ActionSequenceIntention')->orderBy('timeframe_start');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function terrarium()
    {
        return $this->belongsTo('App\Terrarium');
    }

    /**
     * Generates an associated Action Sequence Intention
     *
     * @param $type
     * @param $intention
     * @param null $minimum_timeout_minutes
     * @param null $timeframe_start
     * @param null $timeframe_end
     * @return mixed
     */
    public function generateIntention($type,
                                      $intention,
                                      $minimum_timeout_minutes = null,
                                      $timeframe_start = null,
                                      $timeframe_end = null)
    {
        $minimum_timeout_minutes = is_null($minimum_timeout_minutes) ?
            Carbon::parse(env('DEFAULT_DAY_START', '08:00:00'))->format('H:i:s') : $minimum_timeout_minutes;

        $timeframe_start = is_null($timeframe_start) ? env('DEFAULT_DAY_START', '08:00:00'): $timeframe_start;
        $timeframe_end = is_null($timeframe_end) ? env('DEFAULT_DAY_END', '08:00:00'): $timeframe_end;

        $asi = ActionSequenceIntention::create([
            'name' => 'ASI_' . $this->name . '_' . $minimum_timeout_minutes,
            'type' => $type,
            'intention' => $intention,
            'minimum_timeout_minutes' => $minimum_timeout_minutes,
            'timeframe_start' => $timeframe_start,
            'timeframe_end' => $timeframe_end,
            'action_sequence_id' => $this->id
        ]);

        return $asi;
    }

    /**
     * @param $template
     */
    public function generateIntentionsByTemplate($template)
    {
        switch ($template) {

            case self::TEMPLATE_IRRIGATION:
                $this->generateIntention(ActionSequenceIntention::TYPE_HUMIDITY_PERCENT, ActionSequenceIntention::INTENTION_INCREASE);
                break;

            case self::TEMPLATE_VENTILATE:
                $this->generateIntention(ActionSequenceIntention::TYPE_HUMIDITY_PERCENT, ActionSequenceIntention::INTENTION_DECREASE);
                break;

            case self::TEMPLATE_HEAT_UP:
                $this->generateIntention(ActionSequenceIntention::TYPE_TEMPERATURE_CELSIUS, ActionSequenceIntention::INTENTION_INCREASE);
                break;

            case self::TEMPLATE_COOL_DOWN:
                $this->generateIntention(ActionSequenceIntention::TYPE_TEMPERATURE_CELSIUS, ActionSequenceIntention::INTENTION_DECREASE);
                break;

        }
    }

    /**
     * Tries to generate actions for components matching the defined template.
     * E.g. the irrigation template activates associated valves and pumps.
     *
     * @param $template
     * @return boolean
     */
    public function generateActionsByTemplate($template)
    {
        switch ($template) {

            case self::TEMPLATE_IRRIGATION:

                $custom_components = CustomComponentType::getCustomComponentsByIntention(
                    ActionSequenceIntention::TYPE_HUMIDITY_PERCENT,
                    ActionSequenceIntention::INTENTION_INCREASE,
                    $this->terrarium->custom_components()->getQuery()
                );

                $this->generateActionsForComponentsAndAppend($custom_components);

                foreach ($this->terrarium->valves->filter(function ($v) { return $v->active(); }) as $valve) {
                    $action = $valve->generateActionForSequence($this->duration_minutes, 'running', $this);
                    $this->appendAction($action);

                    if (!is_null($valve->pump) && $valve->pump->active()) {
                        $action = $valve->pump->generateActionForSequence($this->duration_minutes, 'running', $this);
                        $this->appendAction($action);
                    }
                }

                return true;

            case self::TEMPLATE_VENTILATE:

                $custom_components = CustomComponentType::getCustomComponentsByIntention(
                    ActionSequenceIntention::TYPE_HUMIDITY_PERCENT,
                    ActionSequenceIntention::INTENTION_DECREASE,
                    $this->terrarium->custom_components()->getQuery()
                );

                return $this->generateActionsForComponentsAndAppend($custom_components);

            case self::TEMPLATE_HEAT_UP:

                $custom_components = CustomComponentType::getCustomComponentsByIntention(
                    ActionSequenceIntention::TYPE_TEMPERATURE_CELSIUS,
                    ActionSequenceIntention::INTENTION_INCREASE,
                    $this->terrarium->custom_components()->getQuery()
                );

                return $this->generateActionsForComponentsAndAppend($custom_components);

            case self::TEMPLATE_COOL_DOWN:

                $custom_components = CustomComponentType::getCustomComponentsByIntention(
                    ActionSequenceIntention::TYPE_TEMPERATURE_CELSIUS,
                    ActionSequenceIntention::INTENTION_DECREASE,
                    $this->terrarium->custom_components()->getQuery()
                );


                $this->generateActionsForComponentsAndAppend($custom_components);

                foreach ($this->terrarium->valves->filter(function ($v) { return $v->active(); }) as $valve) {
                    $action = $valve->generateActionForSequence($this->duration_minutes, 'running', $this);
                    $this->appendAction($action);

                    if (!is_null($valve->pump) && $valve->pump->active()) {
                        $action = $valve->pump->generateActionForSequence($this->duration_minutes, 'running', $this);
                        $this->appendAction($action);
                    }
                }

                return true;

            default:

                return false;
        }
    }

    /**
     * Generates Actions for all passed components
     * and appends them to the sequence.
     *
     * @param $components
     * @return boolean
     */
    public function generateActionsForComponentsAndAppend($components)
    {
        if ($components->count() < 1) {
            return false;
        }

        foreach ($components as $component) {
            $this->appendAction(
                $component->generateActionForSequence(
                    $this->duration_minutes,
                    $component->getDefaultRunningState(),
                    $this
                ),
                false
            );
        }

        return true;
    }

    /**
     * Assigns an action to this action sequence
     * and automatically sets sequence_sort_id
     *
     * @param Action $action
     * @param bool $auto_wait If the wait_for_started_action_id will be set to the previous action by sequence_sort_id
     */
    public function appendAction(Action $action, $auto_wait = true)
    {
        $action->action_sequence_id = $this->id;

        $sequence_prev = $this->actions()->orderBy('sequence_sort_id', 'desc')->get()->first();
        $action->sequence_sort_id = is_null($sequence_prev) ? 1 : $sequence_prev->sequence_sort_id + 1;

        if ($auto_wait) {
            $action->wait_for_started_action_id = is_null($sequence_prev) ? null : $sequence_prev->id;
        }

        $action->save();
    }


    /**
     * @return bool
     */
    public static function stopped()
    {
        return !is_null(System::property('stop_all_action_sequences'));
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'playlist-play';
    }

    /**
     *
     */
    public function url()
    {
        return url('action_sequences/' . $this->id);
    }
}
