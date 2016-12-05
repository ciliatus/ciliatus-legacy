<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Log
 * @package App
 */
class Log extends Model
{
    use Traits\Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['source_type', 'source_id', 'target_type', 'target_id', 'associatedWith_type', 'associatedWith_id', 'action'];

    /**
     * @param array $attributes
     * @return static
     */
    public static function create(array $attributes = [])
    {

        $new = parent::create($attributes);

        if (!Auth::guest()) {
            $new->source_type = 'User';
            $new->source_id = Auth::user()->id;
        }

        $new->save();

        return $new;
    }

    /**
     * @return null
     */
    public function source()
    {
        if (!is_null($this->source_type) && !is_null($this->source_id)) {
            $class_name = 'App\\' . $this->source_type;
            if (class_exists($class_name)) {
                $source = $class_name::find($this->source_id);
                if (is_null($source)) {
                    return null;
                }
                return $source;
            }
            else {
                return null;
            }
        }

        return null;
    }

    /**
     * @return null
     */
    public function target()
    {
        if (!is_null($this->target_type) && !is_null($this->target_id)) {
            $class_name = 'App\\' . $this->target_type;
            if (class_exists($class_name)) {
                $target = $class_name::find($this->target_id);
                if (is_null($target)) {
                    return null;
                }
                return $target;
            }
            else {
                return null;
            }
        }

        return null;
    }

    /**
     * @return null
     */
    public function associatedWith()
    {
        if (!is_null($this->associatedWith_type) && !is_null($this->associatedWith_id)) {
            $class_name = 'App\\' . $this->associatedWith_type;
            if (class_exists($class_name)) {
                $assoc = $class_name::find($this->associatedWith_id);
                if (is_null($assoc)) {
                    return null;
                }
                return $assoc;
            }
            else {
                return null;
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function icon()
    {
        switch ($this->action) {
            case 'create':
                return 'library_add';
            case 'delete':
                return 'delete';
            case 'notify':
            case 'notify_recovered':
                return 'send';
            case 'update':
                return 'update';
            case 'recover':
                return 'file_upload';
            default:
                return 'circle-o';
        }
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('logs/' . $this->id);
    }
}
