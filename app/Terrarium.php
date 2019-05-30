<?php

namespace App;

use App\Events\TerrariumDeleted;
use App\Events\TerrariumUpdated;
use App\Repositories\SensorreadingRepository;
use App\Traits\HasSensors;
use App\Traits\Uuids;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Class Terrarium
 * @package App
 */
class Terrarium extends CiliatusModel
{
    use Uuids, Notifiable, HasSensors;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * @var array
     */
    protected $casts = [
        'notifications_enabled' =>  'boolean',
        'temperature_critical'  =>  'boolean',
        'humidity_critical'     =>  'boolean',
        'heartbeat_critical'    =>  'boolean'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'cooked_temperature_celsius_age_minutes',
        'cooked_humidity_percent_age_minutes'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'display_name'
    ];

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'updated' => TerrariumUpdated::class,
        'deleting' => TerrariumDeleted::class
    ];

    /**
     * @return bool|null
     * @throws \Exception
     */
    public function delete()
    {
        $this->action_sequences()->delete();
        return parent::delete();
    }

    /**
     * @return mixed
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'Terrarium');
    }

    /**
     * @return BelongsTo
     */
    public function room()
    {
        return $this->belongsTo('App\Room');
    }

    /**
     * @return HasMany
     */
    public function valves()
    {
        return $this->hasMany('App\Valve')->with('pump', 'controlunit');
    }

    /**
     * @return mixed
     */
    public function custom_components()
    {
        return $this->hasMany('App\CustomComponent', 'belongsTo_id')->with('controlunit')->where('belongsTo_type', 'Terrarium');
    }

    /**
     * @return HasMany
     */
    public function animals()
    {
        return $this->hasMany('App\Animal');
    }

    /**
     * @return HasMany
     */
    public function action_sequences()
    {
        return $this->hasMany('App\ActionSequence')->with('actions', 'schedules', 'triggers', 'intentions');
    }


    /**
     * @return mixed
     */
    public function background_image_path()
    {
        $file_id = null;
        $prop = $this->property('generic', 'background_file_id');
        if (is_null($prop)) {
            foreach ($this->animals as $animal) {
                $prop = $animal->property('generic', 'background_file_id');
                if (!is_null($prop)) {
                    $file_id = $prop->value;
                    break;
                }
            }
        }
        else {
            $file_id = $prop->value;
        }

        if (is_null($file_id)) {
            return null;
        }

        /**
         * @var File $file
         */
        $file = File::find($file_id);
        if (is_null($file)) {
            return null;
        }

        if ($thumb = $file->thumb()) {
        return $thumb->path_external();
    }

        \Log::warning('Using non-thumb as Terrarium background. Terrarium: ' . $this->id . ', File: ' . $file->id);
        return $file->path_external();
    }

    /**
     * @return array
     */
    public function capabilities()
    {
        $capabilities = [];
        $capabilities[ActionSequence::TEMPLATE_IRRIGATION] =
            $this->hasComponentsForActionSequenceTemplate(ActionSequence::TEMPLATE_IRRIGATION);
        $capabilities[ActionSequence::TEMPLATE_VENTILATE] =
            $this->hasComponentsForActionSequenceTemplate(ActionSequence::TEMPLATE_VENTILATE);
        $capabilities[ActionSequence::TEMPLATE_HEAT_UP] =
            $this->hasComponentsForActionSequenceTemplate(ActionSequence::TEMPLATE_HEAT_UP);
        $capabilities[ActionSequence::TEMPLATE_COOL_DOWN] =
            $this->hasComponentsForActionSequenceTemplate(ActionSequence::TEMPLATE_COOL_DOWN);

        return $capabilities;
    }

    /**
     * Returns true if the terrarium has components
     * which can serve the defined template.
     *
     * @param $template
     * @return bool
     */
    public function hasComponentsForActionSequenceTemplate($template)
    {
        switch ($template) {

            case ActionSequence::TEMPLATE_IRRIGATION:

                $custom_components = CustomComponentType::getCustomComponentsByIntention(
                    ActionSequenceIntention::TYPE_HUMIDITY_PERCENT,
                    ActionSequenceIntention::INTENTION_INCREASE,
                    $this->custom_components()->getQuery()
                );

                return $this->valves->filter(function ($v) { return $v->active(); })->count() + $custom_components->count() > 0;

            case ActionSequence::TEMPLATE_VENTILATE:

                $custom_components = CustomComponentType::getCustomComponentsByIntention(
                    ActionSequenceIntention::TYPE_HUMIDITY_PERCENT,
                    ActionSequenceIntention::INTENTION_DECREASE,
                    $this->custom_components()->getQuery()
                );

                return $custom_components->count() > 0;

            case ActionSequence::TEMPLATE_HEAT_UP:

                $custom_components = CustomComponentType::getCustomComponentsByIntention(
                    ActionSequenceIntention::TYPE_TEMPERATURE_CELSIUS,
                    ActionSequenceIntention::INTENTION_INCREASE,
                    $this->custom_components()->getQuery()
                );

                return $custom_components->count() > 0;

            case ActionSequence::TEMPLATE_COOL_DOWN:

                $custom_components = CustomComponentType::getCustomComponentsByIntention(
                    ActionSequenceIntention::TYPE_TEMPERATURE_CELSIUS,
                    ActionSequenceIntention::INTENTION_DECREASE,
                    $this->custom_components()->getQuery()
                );

                return $custom_components->count() > 0;
        }

        return false;
    }

    /**
     * Generate an Action Sequence for the desired template for this terrarium.
     * Returns Action Sequence on success and
     * false if no compatible components could be found.
     *
     * @param      $template
     * @param      $duration_minutes
     * @param bool $runonce
     * @return bool|ActionSequence
     * @throws \Exception
     */
    public function generateActionSequenceByTemplate($template, $duration_minutes, $runonce = false)
    {
        if (!$this->hasComponentsForActionSequenceTemplate($template)) {
            return false;
        }

        $action_sequence = ActionSequence::create([
            'name' => trans('labels.' . $template) . ' ' . $this->display_name . ' ' . $duration_minutes . 'm',
            'terrarium_id' => $this->id,
            'duration_minutes' => $duration_minutes
        ]);

        $action_sequence->runonce = $runonce;
        $action_sequence->duration_minutes = $duration_minutes;
        $action_sequence->save();

        if ($action_sequence->generateActionsByTemplate($template)) {
            return $action_sequence;
        }
        else {
            $action_sequence->delete();
            return false;
        }
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'trackpad';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('terraria/' . $this->id);
    }
}
