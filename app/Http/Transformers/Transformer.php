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
     * @param $item
     * @return array
     */
    protected function parseTimestamps($item)
    {
        $now = Carbon::now();
        $created = Carbon::parse($item['created_at']);
        $updated = Carbon::parse($item['updated_at']);

        return [
            'created' => $item['created_at'],
            'updated' => $item['updated_at'],
            'created_diff' => [
                'days' => $now->diffInDays($created),
                'hours' => $now->diffInHours($created),
                'minutes' => $now->diffInMinutes($created)
            ],
            'updated_diff' => [
                'days' => $now->diffInDays($updated),
                'hours' => $now->diffInHours($updated),
                'minutes' => $now->diffInMinutes($updated)
            ]
        ];
    }

}