<?php


namespace App\Http\Transformers;
use Carbon\Carbon;

/**
 * Class AnimalWeighingEventTransformer
 * @package App\Http\Transformers
 */
class AnimalWeighingEventTransformer extends Transformer
{


    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $return = [
            'id'    => $item['id'],
            'type'  => $item['name'],
            'amount'  => $item['value'],
            'timestamps' => $this->parseTimestamps($item)
        ];

        $return = $this->addCiliatusSpecificFields($return, $item);

        if (isset($item['animal'])) {
            $return['animal'] = (new AnimalTransformer())->transform($item['animal']);
        }

        return $return;
    }
}