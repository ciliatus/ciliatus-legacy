<?php

namespace App;

use App\Events\AnimalDeleted;
use App\Events\AnimalUpdated;
use App\Repositories\AnimalRepository;
use Auth;
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
     * @var array
     */
    protected $fillable = ['display_name'];

    /**
     * @var array
     */
    protected  $casts = [
        'notifications_enabled' =>  'boolean'
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     *
     */
    public function delete()
    {
        broadcast(new AnimalDeleted($this));

        parent::delete();
    }


    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $result = parent::save($options);

        broadcast(new AnimalUpdated((new AnimalRepository($this))->show()));

        return $result;
    }

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
     * @return mixed
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'Animal');
    }

    /**
     * @return mixed
     */
    public function events()
    {
        return $this->hasMany('App\Event', 'belongsTo_id')->where('belongsTo_type', 'Animal');
    }

    /**
     * @return mixed
     */
    public function feedings()
    {
        return $this->events()->where('type', 'AnimalFeeding')
                              ->orderBy('created_at', 'desc');
    }

    /**
     * @return mixed
     */
    public function feeding_schedules()
    {
        return $this->properties()->where('type', 'AnimalFeedingSchedule');
    }

    /**
     * @return mixed
     */
    public function weighings()
    {
        return $this->events()->where('type', 'AnimalWeighing')
            ->orderBy('created_at', 'desc');
    }

    /**
     * @return mixed
     */
    public function weighing_schedules()
    {
        return $this->properties()->where('type', 'AnimalWeighingSchedule');
    }

    /**
     * @return string
     */
    public function gender_icon()
    {
        switch ($this->gender) {
            case 'male':
                return 'mars';
                break;
            case 'female':
                return 'venus';
                break;
            default:
                return 'genderless';
        }
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
        if ($compare_at->diffInYears($this->birth_date) >= 2) {
            $amount = $compare_at->diffInYears($this->birth_date);
            return [$amount, 'years'];

        }
        if ($compare_at->diffInMonths($this->birth_date) > 1) {
            $amount = $compare_at->diffInMonths($this->birth_date);
            return [$amount, 'months'];
        }

        $amount = $compare_at->diffInDays($this->birth_date);
        return [$amount, 'days'];
    }

    /**
     * @param null $type
     * @return mixed
     */
    public function last_feeding($type = null)
    {
        if (is_null($type)) {
            return $this->feedings()->limit(1)->get()->first();
        }
        else {
            return $this->feedings()->where('name', $type)->limit(1)->get()->first();
        }
    }

    /**
     * @return mixed
     */
    public function last_weighing()
    {
        return $this->weighings()->limit(1)->get()->first();
    }

    /**
     * @return string
     */
    public function icon()
    {
        return 'pets';
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('animals/' . $this->id);
    }
}
