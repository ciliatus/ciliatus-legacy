<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Animal
 * @package App
 */
class Animal extends Model
{
    use Traits\Uuids;

    /**
     * @var array
     */
    public $timestamps = ['created_at', 'updated_at', 'birth_date', 'death_date'];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * @return mixed
     */
    public function terrarium()
    {
        return $this->belongsTo('App\Terrarium');
    }

    public function getAge()
    {
        if (Carbon::now()->diffInYears(Carbon::parse($this->birth_date)) > 3)
            return ['unit' => 'years', 'value' => Carbon::now()->diffInYears(Carbon::parse($this->birth_date))];
        if (Carbon::now()->diffInMonths(Carbon::parse($this->birth_date)) > 1)
            return ['unit' => 'months', 'value' => Carbon::now()->diffInMonths(Carbon::parse($this->birth_date))];

        return ['unit' => 'days', 'value' => Carbon::now()->diffInDays(Carbon::parse($this->birth_date))];
    }
}
