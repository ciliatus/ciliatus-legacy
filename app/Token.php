<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * Class Model
 *
 * @package App
 * @mixin \Eloquent
 */
class Token extends Model
{
    use Traits\Uuids;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */

    public $incrementing = false;

    /**
     * @param int $length
     * @return string
     */
    public static function generate($length = 32)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $token = '';
        for ($i = 0; $i < $length; $i++) {
            $token .= $chars[(int)rand(0, strlen($chars)-1)];
        }

        return $token;
    }
}
