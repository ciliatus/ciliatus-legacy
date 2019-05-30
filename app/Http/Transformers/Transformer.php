<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:16
 */

namespace App\Http\Transformers;

use App\CiliatusModel;
use App\Factories\TransformerFactory;
use Carbon\Carbon;
use Illuminate\Support\Collection;


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
                'years' => $now->diffInYears($created),
                'months' => $now->diffInMonths($created),
                'weeks' => $now->diffInWeeks($created),
                'days' => $now->diffInDays($created),
                'hours' => $now->diffInHours($created),
                'minutes' => $now->diffInMinutes($created),
                'is_today' => $created->isToday()
            ],
            'updated' => $item['updated_at'],
            'updated_diff' => [
                'years' => $now->diffInYears($created),
                'months' => $now->diffInMonths($created),
                'weeks' => $now->diffInWeeks($created),
                'days' => $now->diffInDays($updated),
                'hours' => $now->diffInHours($updated),
                'minutes' => $now->diffInMinutes($updated),
                'is_today' => $updated->isToday()
            ]
        ];

        foreach ($additional_fields as $k=>$v) {
            if (!isset($item[$k])) {
                continue;
            }

            $return[$v] = $item[$k];
            $date = Carbon::parse($item[$k]);
            $return[$v . '_diff'] = [
                'years' => $now->diffInYears($date),
                'months' => $now->diffInMonths($date),
                'weeks' => $now->diffInWeeks($date),
                'days' => $now->diffInDays($date),
                'hours' => $now->diffInHours($date),
                'minutes' => $now->diffInMinutes($date),
                'is_today' => $date->isToday()
            ];
        }

        return $return;
    }

    /**
     * Tries to find the object defined by belongsTo_type and belongsTo_id.
     * If a repository is present, the object will be piped through that.
     * If a matching Transformer exists the object will be transformed.
     *
     * On error the original item will be returned
     * Otherwise the field belongsTo will be added
     * to the original item and then returned
     *
     * @param $item
     * @return mixed
     */
    protected function parseBelongsTo($item)
    {
        if (isset($item['belongsTo_type']) && isset($item['belongsTo_id'])) {

            $object_name = 'App\\' . $item['belongsTo_type'];
            $repo_name = 'App\\Repositories\\' . $item['belongsTo_type'] . 'Repository';
            $transformer_name = 'App\\Http\\Transformers\\' . $item['belongsTo_type'] . 'Transformer';

            if (class_exists($object_name)) {
                $object = $object_name::find($item['belongsTo_id']);
            }
            else {
                return $item;
            }

            if (is_null($object)) {
                return $item;
            }

            if (class_exists($repo_name)) {
                $object = (new $repo_name($object))->show();
            }

            if (class_exists($transformer_name)) {
                $transformer = new $transformer_name();
                $item['belongsTo'] = $transformer->transform($object->toArray());
            }

            return $item;
        }

        return $item;
    }


    /**
     * @param array $return
     * @param array $item
     * @param array $exclude
     * @return array
     */
    protected function addCiliatusSpecificFields(array $return, array $item, array $exclude = [])
    {
        if (isset($item['properties']) && !in_array('properties', $exclude)) {
            $return['properties'] = (new PropertyTransformer())->transformCollection(
                is_array($item['properties']) ? $item['properties'] : $item['properties']->toArray()
            );
        }
        if (isset($item['icon']) && !in_array('icon', $exclude)) {
            $return['icon'] = $item['icon'];
        }
        if (isset($item['web_base_url']) && !in_array('web_base_url', $exclude)) {
            $return['web_base_url'] = $item['web_base_url'];
        }
        if (isset($item['api_base_url']) && !in_array('api_base_url', $exclude)) {
            $return['api_base_url'] = $item['api_base_url'];
        }
        if (isset($item['url']) && !in_array('url', $exclude)) {
            $return['url'] = $item['url'];
        }
        if (isset($item['active']) && !in_array('active', $exclude)) {
            $return['active'] = $item['active'];
        }
        if (isset($item['class']) && !in_array('class', $exclude)) {
            $return['class'] = $item['class'];
        }

        $return['created_at'] = $item['created_at'];
        $return['updated_at'] = $item['updated_at'];

        if (isset($item['related_models'])) {
            foreach ($item['related_models'] as $relation=>$objects) {
                if (is_a($objects, Collection::class)) {
                    $transformer = TransformerFactory::get($objects->first());
                    $return[$relation] = $transformer->transformCollection($objects->toArray());
                }
                else {
                    $object = $objects;
                    $transformer = TransformerFactory::get($object);
                    $return[$relation] = $transformer->transform($object->toArray());
                }
            }
        }

        return $return;
    }

    protected function addSensorSpecificFields(array $return, array $item, array $exclude = [])
    {
        if (isset($item['temperature_celsius_history'])) {
            $return['temperature_history'] = $item['temperature_celsius_history'];
        }
        else {
            $return['temperature_history'] = [];
        }

        if (isset($item['humidity_percent_history'])) {
            $return['humidity_history'] = $item['humidity_percent_history'];
        }
        else {
            $return['humidity_history'] = [];
        }

        if (isset($item['default_background_filepath'])) {
            $return['default_background_filepath'] = $item['default_background_filepath'];
        }


        $return = array_merge($return, [
            'temperature_critical' => isset($item['temperature_critical']) ? $item['temperature_critical'] : null,
            'humidity_critical' => isset($item['humidity_critical']) ? $item['humidity_critical'] : null,
            'heartbeat_critical' => isset($item['heartbeat_critical']) ? $item['heartbeat_critical'] : null,
            'state_ok' => isset($item['state_ok']) ? $item['state_ok'] : null,
            'cooked_temperature_celsius' => $item['cooked_temperature_celsius'],
            'cooked_temperature_celsius_updated_at' => $item['cooked_temperature_celsius_updated_at'],
            'cooked_temperature_celsius_age_minutes' => isset($item['cooked_temperature_celsius_age_minutes']) ? $item['cooked_temperature_celsius_age_minutes'] : null,
            'cooked_humidity_percent' => $item['cooked_humidity_percent'],
            'cooked_humidity_percent_updated_at' => $item['cooked_humidity_percent_updated_at'],
            'cooked_humidity_percent_age_minutes' => isset($item['cooked_humidity_percent_age_minutes']) ? $item['cooked_humidity_percent_age_minutes'] : null
        ]);

        return $return;
    }

}