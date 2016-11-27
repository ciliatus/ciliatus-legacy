<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;

/**
 * Class File
 * @package App
 */
class File extends CiliatusModel
{
    use Traits\Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * Models the File can belong to
     *
     * @var array
     */
    protected static $belongTo_Types = [
        'Terrarium', 'Animal'
    ];

    /**
     * @param array $attributes
     * @return CiliatusModel|File
     */
    public static function create(array $attributes = [])
    {
        $new = parent::create($attributes);
        Log::create([
            'target_type'   =>  explode('\\', get_class($new))[count(explode('\\', get_class($new)))-1],
            'target_id'     =>  $new->id,
            'associatedWith_type' => explode('\\', get_class($new))[count(explode('\\', get_class($new)))-1],
            'associatedWith_id' => $new->id,
            'action'        => 'create'
        ]);

        return $new;
    }

    /**
     *
     */
    public function delete()
    {

        foreach ($this->properties() as $prop) {
            $prop->delete();
        }

        if (file_exists($this->path_internal()))
            unlink($this->path_internal());

        Log::create([
            'target_type'   =>  explode('\\', get_class($this))[count(explode('\\', get_class($this)))-1],
            'target_id'     =>  $this->id,
            'associatedWith_type' => explode('\\', get_class($this))[count(explode('\\', get_class($this)))-1],
            'associatedWith_id' => $this->id,
            'action'        => 'delete'
        ]);

        parent::delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\Property', 'belongsTo_id')->where('belongsTo_type', 'File');
    }

    /**
     * @param $name
     * @return null
     */
    public function property($name)
    {
        foreach ($this->properties as $p) {
            if ($p->name == $name) {
                return $p->value;
            }
        }

        return null;
    }

    /**
     * @return null
     */
    public function belongs_to()
    {
        if (!is_null($this->belongsTo_type) && !is_null($this->belongsTo_id)) {
            $class_name = 'App\\' . $this->belongsTo_type;
            if (class_exists($class_name)) {
                $belongs = $class_name::find($this->belongsTo_id);
                if (is_null($belongs)) {
                    return null;
                }
                return $belongs;
            }
            else {
                return null;
            }
        }

        return null;
    }

    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {

        if (!in_array('silent', $options)) {
            Log::create([
                'target_type' => explode('\\', get_class($this))[count(explode('\\', get_class($this))) - 1],
                'target_id' => $this->id,
                'associatedWith_type' => explode('\\', get_class($this))[count(explode('\\', get_class($this))) - 1],
                'associatedWith_id' => $this->id,
                'action' => 'update'
            ]);
        }

        return parent::save($options);
    }

    /**
     * @return string
     */
    public function path_internal()
    {
        return self::joinPath(
            [
                base_path(),
                $this->parent_path,
                $this->name
            ]
        );
    }

    /**
     * @return mixed
     */
    public function path_external()
    {
        return url('files/' . $this->id . '/download');
    }

    /**
     * @return string
     */
    public function icon()
    {
        switch (explode('/', $this->mimetype)[0]) {
            case 'image':
                return 'photo';
            default:
                return 'file-o';
        }
    }

    /**
     * @return string
     */
    public function sizeReadable()
    {
        if ($this->size > pow(1024, 3)) {
            return round($this->size/pow(1024, 3), 2) . ' GB';
        }

        if ($this->size > pow(1024, 2)) {
            return round($this->size/pow(1024, 2), 2) . ' MB';
        }

        if ($this->size > 1024) {
            return round($this->size/1024, 2) . ' KB';
        }

        return $this->size . ' B';


    }

    /**
     * Join an array of partial paths to
     * a valid path by removing or adding
     * slashes
     *
     * @param array $paths
     * @return string
     */
    public static function joinPath(array $paths)
    {
        $paths_trimmed = array_map(
            function($field) {
                $start = substr($field, 0, 1) == '/' ? 1 : 0;
                $end = (substr($field, strlen($field)-1) == '/' ? strlen($field) - 1 : strlen($field)) - $start;
                return substr($field, $start, $end);
            },
            $paths
        );

        return substr($paths[0], 0, 1) == '/' ? '/' . implode('/', $paths_trimmed) : implode('/', $paths_trimmed);
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('files/' . $this->id);
    }



    /**
     * @return array
     */
    public static function belongTo_Types()
    {
        return self::$belongTo_Types;
    }
}
