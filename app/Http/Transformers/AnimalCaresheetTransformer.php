<?php


namespace App\Http\Transformers;

/**
 * Class AnimalCaresheetTransformer
 * @package App\Http\Transformers
 */
class AnimalCaresheetTransformer extends Transformer
{


    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $return = [
            'id'    => $item['id'],
            'title'  => $item['name'],
            'timestamps' => $this->parseTimestamps($item)
        ];

        $return = $this->addCiliatusSpecificFields($return, $item);

        return $return;
    }
}