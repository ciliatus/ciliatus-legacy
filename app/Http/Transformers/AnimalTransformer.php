<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;
use Carbon\Carbon;

/**
 * Class AnimalTransformer
 * @package App\Http\Transformers
 */
class AnimalTransformer extends Transformer
{
    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $return = [
            'id'    => $item['id'],
            'latin_name' => isset($item['lat_name']) ? $item['lat_name'] : '',
            'common_name' => isset($item['common_name']) ? $item['common_name'] : '',
            'display_name' => $item['display_name'],
            'birth_date' => isset($item['birth_date']) ? Carbon::parse($item['birth_date'])->format('d.m.Y') : null,
            'death_date' => isset($item['death_date']) ? Carbon::parse($item['death_date'])->format('d.m.Y') : null,
            'gender' => isset($item['gender']) ? $item['gender'] : '',
            'terrarium_id' => isset($item['terrarium_id']) ? $item['terrarium_id'] : '',
            'timestamps' => $this->parseTimestamps($item)
        ];

        $return = $this->addCiliatusSpecificFields($return, $item);

        if (isset($item['terrarium'])) {
            $return['terrarium'] = $item['terrarium'];
        }

        if (isset($item['age'])) {
            $return['age'] = $item['age'];
        }

        if (isset($item['age_value'])) {
            $return['age_value'] = $item['age_value'];
        }

        if (isset($item['age_unit'])) {
            $return['age_unit'] = $item['age_unit'];
        }

        if (isset($item['gender_icon'])) {
            $return['gender_icon'] = $item['gender_icon'];
        }

        if (isset($item['default_background_filepath'])) {
            $return['default_background_filepath'] = $item['default_background_filepath'];
        }

        if (isset($item['last_feeding'])) {
            $return['last_feeding'] = [
                    'name' => $item['last_feeding']['name'],
                    'value' => $item['last_feeding']['value'],
                    'timestamps' => $this->parseTimestamps($item['last_feeding'])
                ];
        }

        if (isset($item['last_weighing'])) {
            $return['last_weighing'] = [
                'name' => $item['last_weighing']['name'],
                'value' => $item['last_weighing']['value'],
                'timestamps' => $this->parseTimestamps($item['last_weighing'])
            ];

            if (isset($item['last_weighing']['trend'])) {
                $return['last_weighing']['trend'] = $item['last_weighing']['trend'];
            }
        }

        if (isset($item['feedings'])) {
            $return['feedings'] = (new AnimalFeedingEventTransformer())->transformCollection($item['feedings']);
        }

        if (isset($item['feeding_schedules'])) {
            $return['feeding_schedules'] = (new AnimalFeedingSchedulePropertyTransformer())->transformCollection($item['feeding_schedules']);
        }

        if (isset($item['weighings'])) {
            $return['weighings'] = (new AnimalWeighingEventTransformer())->transformCollection($item['weighings']);
        }

        if (isset($item['weighing_schedules'])) {
            $return['weighing_schedules'] = (new AnimalWeighingSchedulePropertyTransformer())->transformCollection($item['weighing_schedules']);
        }

        if (isset($item['biography_entries'])) {
            $return['biography_entries'] = (new BiographyEntryEventTransformer())->transformCollection($item['biography_entries']);
        }

        return $return;
    }
}