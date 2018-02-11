<?php

namespace App\Observers;



use App\File;

/**
 * Class FileObserver
 * @package App\Observers
 */
class FileObserver
{

    /**
     * @param File $file
     * @return void
     */
    public function deleting(File $file)
    {
        $file->properties()->delete();

        if (!is_null($file->thumb())) {
            $file->thumb()->delete();
        }

        if (file_exists($file->path_internal())) {
            unlink($file->path_internal());
        }
    }
}