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
            'timestamps' => $this->parseTimestamps($item)
        ];

        $return = $this->addCiliatusSpecificFields($return, $item, []);

        if (isset($item['models'])) {
            $return['models'] = [];
            foreach ($item['models'] as $model) {
                $return['models'][] = $model->enrichAndTransform();
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