<?php


namespace App\Http\Transformers;

/**
 * Class BiographyEntryEventTransformer
 * @package App\Http\Transformers
 */
class BiographyEntryEventTransformer extends Transformer
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
            'text'  => $item['value'],
            'timestamps' => $this->parseTimestamps($item)
        ];

        $return = $this->addCiliatusSpecificFields($return, $item);

        if (isset($item['category'])) {
            $return['category'] = (new CategoryTransformer())->transform(
                (is_array($item['category']) ? $item['category'] : $item['category']->toArray())
            );
        }

        if (isset($item['files'])) {
            $return['files'] = (new FileTransformer())->transformCollection(
                (is_array($item['files']) ? $item['files'] : $item['files']->toArray())
            );
        }

        return $return;
    }
}