<?php

namespace App;

use App\Http\Transformers\GenericTransformer;
use App\Repositories\GenericRepository;
use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        $new = new Log($attributes);
        $new->save();

        if (!Auth::guest()) {
            $new->source_type = 'User';
            $new->source_id = Auth::user()->id;
        }

        if (!is_null($new->source_type) && !is_null($new->source_type)) {
            if (!is_null($new->source)) {
                $new->source_name = $new->source->name;
            }
        }

        if (!is_null($new->target_type) && !is_null($new->target_id)) {
            if (!is_null($new->name)) {
                $new->target_name = $new->target->name;
            }
        }

        if (!is_null($new->associatedWith_type) && !is_null($new->associatedWith_id)) {
            if (!is_null($new->associated)) {
                $new->associatedWith_name = $new->associated->name;
            }
        }

        $new->save();

        return $new;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'Log');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function source()
    {
        if (is_null($this->source_type)) {
            return null;
        }
        return $this->belongsTo('App\\' . $this->source_type, 'source_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function target()
    {
        if (is_null($this->target_type)) {
            return null;
        }
        return $this->belongsTo('App\\' . $this->target_type, 'target_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function associated()
    {
        if (is_null($this->associatedWith_type)) {
            return null;
        }
        return $this->belongsTo('App\\' . $this->associatedWith_type, 'associatedWith_id');
    }

    /**
     *
     * @param $transform boolean
     */
    public function addSourceTargetAssociated($transform = false)
    {
        if (!is_null($this->source_type) && !is_null($this->source_id)) {
            $obj = null;

            try {
                $obj = $this->source;
            }
            catch (ModelNotFoundException $ex) {
                \Log::debug('source not found ' . $ex->getMessage());
            }

            if (!is_null($obj)) {
                $this->addSource($transform);
            }
        }

        if (!is_null($this->target_type) && !is_null($this->target_id)) {
            $obj = null;

            try {
                $obj = $this->target;
            }
            catch (ModelNotFoundException $ex) {
                \Log::debug('target not found ' . $ex->getMessage());
            }

            if (!is_null($obj)) {
                $this->addTarget($transform);
            }
        }
        
        if (!is_null($this->associatedWith_type) && !is_null($this->associatedWith_id)) {
            $obj = null;

            try {
                $obj = $this->associated;
            }
            catch (ModelNotFoundException $ex) {
                \Log::debug('assoc not found ' . $ex->getMessage());
            }

            if (!is_null($obj)) {
                $this->addAssociated($transform);
            }
        }
    }

    /**
     * @param bool $transform
     */
    public function addSource($transform = false)
    {
        $this->source = (new GenericRepository($this->source))->show();
        if ($transform) {
            $this->source = (new GenericTransformer())->transform($this->source);
        }
    }

    /**
     * @param bool $transform
     */
    public function addTarget($transform = false)
    {
        $this->target = (new GenericRepository($this->target))->show();
        if ($transform) {
            $this->target = (new GenericTransformer())->transform($this->target);
        }
    }

    /**
     * @param bool $transform
     */
    public function addAssociated($transform = false)
    {
        $this->associated = (new GenericRepository($this->associated))->show();
        if ($transform) {
            $this->associated = (new GenericTransformer())->transform($this->associated);
        }
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
