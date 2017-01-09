<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:16
 */

namespace App\Http\Transformers;

use Carbon\Carbon;


/**
 * Class Transformer
 * @package App\Http\Transformers
 */
abstract class Transformer
{

    /**
     * @param array $items
     * @return array
     */
    public function transformCollection(array $items)
    {
        return array_map([$this, 'transform'], $items);
    }

    /**
     * @param $item
     * @return mixed
     */
    public abstract function transform($item);

    /**
     * Creates an array of times and time diffs in relation to the current datetime
     * By default the db fields created_at and updated_at will be used an transformed
     * to created/updated containing the formatted date
     * and created_diff/updated_diff containing the diff in days, hours and minutes.
     * Additional fields can be passed using the $additional_fields parameter.
     *
     * @param $item
     * @param $additional_fields array  Expects an associative array
     *                                  of additional fields from db (key)
     *                                  and their desired name in the target array (field)
     * @return array
     */
    protected function parseTimestamps($item, $additional_fields = [])
    {
        $now = Carbon::now();
        $created = Carbon::parse($item['created_at']);
        $updated = Carbon::parse($item['updated_at']);

        $return = [
            'created' => $item['created_at'],
            'created_diff' => [
                'days' => $now->diffInDays($created),
                'hours' => $now->diffInHours($created),
                'minutes' => $now->diffInMinutes($created)
            ],
            'updated' => $item['updated_at'],
            'updated_diff' => [
                'days' => $now->diffInDays($updated),
                'hours' => $now->diffInHours($updated),
                'minutes' => $now->diffInMinutes($updated)
            ]
        ];

        foreach ($additional_fields as $k=>$v) {
            $return[$v] = $item[$k];
            $date = Carbon::parse($item[$k]);
            $return[$v . '_diff'] = [
                'days' => $now->diffInDays($date),
                'hours' => $now->diffInHours($date),
                'minutes' => $now->diffInMinutes($date)
            ];
        }

        return $return;
    }

}