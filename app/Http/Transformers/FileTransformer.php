<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;

/**
 * Class FileTransformer
 * @package App\Http\Transformers
 */
class FileTransformer extends Transformer
{


    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $return = [
            'id'    => $item['id'],
            'name' => $item['name'],
            'display_name' => $item['display_name'],
            'size' => $item['size'],
            'state' => $item['state'],
            'mimetype' => $item['mimetype'],
            'timestamps' => $this->parseTimestamps($item),
            'icon'          =>  isset($item['icon']) ? $item['icon'] : '',
            'url'           =>  isset($item['url'])? $item['url'] : ''
        ];

        if (isset($item['properties']) && is_array($item['properties'])) {
            foreach ($item['properties'] as $property) {
                $return['properties'][$property['name']] = $property['value'];
            }
        }

        if (isset($item['path_internal'])) {
            $return['path_internal'] = $item['path_internal'];
        }

        if (isset($item['path_external'])) {
            $return['path_external'] = $item['path_external'];
        }

        if (isset($item['thumb'])) {
            $return['thumb'] = (new FileTransformer())->transform($item['thumb']->toArray());
        }

        if (explode('/', $item['mimetype'])[0] == 'image') {
            $return['is_image'] = true;
        }

        return $return;
    }
}