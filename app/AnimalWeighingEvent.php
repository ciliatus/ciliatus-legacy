<?php

namespace App;

use App\Events\AnimalWeighingEventDeleted;
use App\Events\AnimalWeighingEventUpdated;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;

/**
 * Class AnimalWeighingEvent
 * @package App
 */
class AnimalWeighingEvent extends Event
{
    use Uuids, Notifiable;

    /**
     * @var string
     */
    protected $table = 'events';

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'updated' => AnimalWeighingEventUpdated::class,
        'deleting' => AnimalWeighingEventDeleted::class
    ];

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('type', function (Builder $builder) {
            $builder->where('type', 'AnimalWeighing');
        });
    }

    /**
     * @param int $days
     * @return float
     */
    public function trend($days = 30)
    {

        $to = $this->created_at;
        $from = (clone $to)->subDays($days);

        /*
         * If an animal feeding exactly $days prior is found -> compare and return
         */
        $exact_match = AnimalFeedingEvent::whereDate('created_at', $from)->get()->first();
        if (!is_null($exact_match) && $exact_match->value) {
            return round(((int)$this->value - (int)$exact_match->value) / (int)$this->value * 100, 1);
        }

        /*
         * Find first feeding before and after $from
         */
        if (is_null($this->belongsTo_object())) {
            return 0.0;
        }

        $first_before = $this->belongsTo_object()
                             ->weighings()
                             ->whereDate('created_at', '<', $from)
                             ->where('id', '!=', $this->id)
                             ->orderBy('created_at', 'DESC')
                             ->limit(1)
                             ->get()->first();
        $first_after = $this->belongsTo_object()
                            ->weighings()
                            ->whereDate('created_at', '>', $from)
                            ->where('id', '!=', $this->id)
                            ->orderBy('created_at')
                            ->limit(1)
                            ->get()->first();

        /*
         * Calculate timespans of the two feedings to $from
         */
        $span_before = is_null($first_before) ? 0 : $first_before->created_at->diffInHours($from)/24;
        $span_after  = is_null($first_after)  ? 0 : $first_after->created_at->diffInHours($from)/24;
        $span_total = $span_before + $span_after;

        /*
         * Calculate how close the feedings are to $from compared to each other
         */
        $percent_before = $span_before == 0 ? 0 : $span_before / $span_total * 100;
        $percent_after =  $span_after  == 0 ? 0 : $span_after  / $span_total * 100;

        /*
         * Calculate the weight at $from by adding the two surrounding feedings
         * after proportionately to their respective timespan to $from
         */
        $first_before_value = is_null($first_before) ? 0 : (double)$first_before->value;
        $first_after_value  = is_null($first_after)  ? 0 : (double)$first_after->value;

        $value_from = ($first_before_value / 100 * $percent_before) + ($first_after_value / 100 * $percent_after);

        /*
         * Calculate and return resulting weight trend in percent
         */
        return round(($this->value - $value_from) / $this->value * 100, 1);

    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'event';
    }

    /**
     * @return string
     */
    public function url()
    {
        return url('animal_feedings/' . $this->id);
    }
}
