<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Animal
 * @package App
 */
class Animal extends CiliatusModel
{
    use Traits\Uuids;

    /**
     * @var array
     */
    protected  $dates = ['created_at', 'updated_at', 'birth_date', 'death_date'];

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

    /**
     * @return mixed
     */
    public function files()
    {
        return $this->hasMany('App\File', 'belongsTo_id')->where('belongsTo_type', 'Animal');
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'paw';
    }

    public function url()
    {
        return url('animals/' . $this->id);
    }

    /**
     * @return array
     */
    public function getAge()
    {
        if (is_null($this->death_date)) {
            $compare_at = Carbon::now();
        }
        else {
            $compare_at = $this->death_date;
        }
        if ($compare_at->diffInYears($this->birth_date) > 3)
            return ['unit' => 'years', 'value' => $compare_at->diffInYears($this->birth_date)];
        if ($compare_at->diffInMonths($this->birth_date) > 1)
            return ['unit' => 'months', 'value' =>$compare_at->diffInMonths($this->birth_date)];

        return ['unit' => 'days', 'value' => $compare_at->diffInDays($this->birth_date)];
    }
}
