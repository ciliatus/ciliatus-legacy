<?php

namespace App;

use Carbon\Carbon;
use ErrorException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;
use Webpatser\Uuid\Uuid;

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
     * @var array
     */
    protected $fillable = [
        'name', 'belongsTo_type', 'belongsTo_id', 'user_id', 'state'
    ];

    /**
     * @param array $attributes
     * @return File|CiliatusModel
     * @throws ErrorException
     */
    public static function create(array $attributes = [])
    {
        $file = new File($attributes);
        $file->save();
        $file->name = $file->id . '.' . $file->name;
        $file->parent_path = File::generateParentPaths();
        $file->state = 'Creating';
        $file->save();

        return $file;
    }

    /**
     * @param Request $request
     * @param $user_id
     * @return CiliatusModel|File
     */
    public static function createFromRequest(Request $request, $user_id)
    {
        $file = File::create([
            'name'      =>  strtolower($request->file('file')->getClientOriginalExtension()),
            'user_id'   =>  $user_id
        ]);

        $file->display_name = $request->file('file')->getClientOriginalName();
        $file->mimetype = $request->file('file')->getClientMimeType();
        $file->size = $request->file('file')->getClientSize();
        $file->move($request);
        $file->save();

        if (explode('/', $file->mimetype)[0] == 'image') {
            $file->generateThumb();
        }

        return $file;
    }


    /**
     *
     */
    public function delete()
    {

        foreach ($this->properties() as $prop) {
            $prop->delete();
        }

        if (!is_null($this->thumb())) {
            $this->thumb()->delete();
        }

        if (file_exists($this->path_internal()))
            unlink($this->path_internal());

        parent::delete();
    }

    /**
     * @param $new_display_name
     * @return File|Model|CiliatusModel
     */
    public function copy($new_display_name)
    {
        $file = $this->replicate();
        $file->save();
        $file->name = str_replace($this->id, $file->id, $file->name);
        $file->parent_path = File::generateParentPaths();
        $file->display_name = $new_display_name;
        $file->save();
        copy($this->path_internal(), $file->path_internal());

        return $file;
    }

    /**
     *
     */
    public function generateThumb()
    {
        $thumb = $this->copy($this->display_name . '_thumb');
        Image::load($thumb->path_internal())
            ->fit(Manipulations::FIT_MAX, 400, 400)
            ->save();
        $thumb->state = 'Uploaded';
        $thumb->belongsTo_type = 'File';
        $thumb->belongsTo_id = $this->id;
        $thumb->usage = 'thumb';
        $thumb->size = filesize($thumb->path_internal());
        $thumb->save();

        return $thumb;
    }

    /**
     * @param Request $request
     */
    public function move(Request $request)
    {
        $request->file('file')->move(
            File::joinPath([
                base_path(),
                $this->parent_path
            ]),
            $this->name
        );
        umask(0);
        chmod($this->path_internal(), 0664);

        switch ($request->file('file')->getClientMimeType()) {
            case 'image/jpeg':
                $exif = exif_read_data($this->path_internal(), 0, true);
                if ($exif) {
                    foreach($exif as $key=>$section) {
                        foreach($section as $name=>$value) {
                            if (!is_array($value)) {
                                $fp = Property::create();
                                $fp->belongsTo_type = 'File';
                                $fp->belongsTo_id = $this->id;
                                $fp->type = 'exif';
                                $fp->name = $key.$name;
                                $fp->value = $value;
                                $fp->save();
                            }
                        }
                    }
                }
        }
    }

    /**
     * @return string
     * @throws ErrorException
     */
    private static function generateParentPaths()
    {
        $year_str = Carbon::now()->year;
        $month_str = str_pad(Carbon::now()->month, 2, '0', STR_PAD_LEFT);
        $parent_path = 'storage/app/files';

        /*
         * check whether year/month folder exists
         * create if not
         */
        $parent_path = File::joinPath([
            $parent_path,
            $year_str,
            $month_str
        ]);
        $absolute_path = File::joinPath([
            base_path(),
            $parent_path
        ]);

        if (!is_dir($absolute_path)) {
            try {
                umask(0);
                mkdir($absolute_path, 0774, true);
            }
            catch (ErrorException $ex) {
                throw $ex;
            }
        }

        return $parent_path;
    }

    /**
     * @return mixed
     */
    public function thumb()
    {
        return File::where('belongsTo_type', 'File')->where('belongsTo_id', $this->id)
                   ->where('usage', 'thumb')->get()->first();
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
     * @return array
     */
    public static function belongTo_Types()
    {
        return self::$belongTo_Types;
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
                return 'attach_file';
        }
    }

    /**
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function url()
    {
        return url('files/' . $this->id);
    }
}
