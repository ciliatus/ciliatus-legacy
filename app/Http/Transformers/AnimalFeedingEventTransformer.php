<?php


namespace App\Http\Transformers;
use Carbon\Carbon;

/**
 * Class AnimalFeedingEventTransformer
 * @package App\Http\Transformers
 */
class AnimalFeedingEventTransformer extends Transformer
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