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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany('App\FileProperty');
    }

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
     *
     */
    public function delete()
    {
        foreach ($this->properties() as $prop) {
            $prop->delete();
        }

        if (file_exists($this->path()))
            unlink($this->path());

        parent::delete();
    }

    /**
     * @return string
     */
    public function path()
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

    public function url()
    {
        return url('files/' . $this->id);
    }
}
