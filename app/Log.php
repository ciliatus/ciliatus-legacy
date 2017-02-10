<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Log
 * @package App
 */
class Log extends CiliatusModel
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
        $new->save();

        if (!Auth::guest()) {
            $new->source_type = 'User';
            $new->source_id = Auth::user()->id;
        }

        if (!is_null($new->belongsTo_type) && !is_null($new->belongsTo_id)) {
            $new->source_name = $new->source->name;
        }

        if (!is_null($new->target_type) && !is_null($new->target_id)) {
            $new->target_name = $new->target->name;
        }

        if (!is_null($new->associatedWith_type) && !is_null($new->associatedWith_id)) {
            $new->associatedWith_name = $new->associated->name;
        }

        $new->save();

        return $new;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function source()
    {
        return $this->belongsTo('App\\' . $this->source_type, 'source_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function target()
    {

        return $this->belongsTo('App\\' . $this->target_type, 'target_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function associated()
    {
        return $this->belongsTo('App\\' . $this->associatedWith_type, 'associatedWith_id');
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
