<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;


/**
 * Class TerrariumTransformer
 * @package App\Http\Transformers
 */
class TerrariumTransformer extends Transformer
{


    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $physicalSensorTransformer = new PhysicalSensorTransformer();
        $animalTransformer = new AnimalTransformer();
        $return = [
            'id'    => $item['id'],
            'name'  => $item['name'],
            'friendly_name' => $item['friendly_name']
        ];

        if (isset($item['physical_sensors'])) {
            $return['physical_sensors'] = $physicalSensorTransformer->transformCollection($item['physical_sensors']);
        }

        if (isset($item['animals'])) {
            $return['animals'] = $animalTransformer->transformCollection($item['animals']);
        }



        return $return;
    }
}