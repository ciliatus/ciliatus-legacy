<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class File
 * @package App
 */
class File extends Model
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
}
