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
 * Class ActionSequenceTriggerTransformer
 * @package App\Http\Transformers
 */
class ActionSequenceTriggerTransformer extends Transformer
{


    /**
     * @param $item
     * @return array
     */
    public function transform($item)
    {
        $return = [
            'id'            =>  $item['id'],
            'runonce'       =>  isset($item['runonce']) ? $item['runonce'] : false,
            'states'        => [
                'running' => isset($item['running']) ? $item['running'] : false,
                'trigger_active' => isset($item['trigger_active']) ? $item['trigger_active'] : false,
            ],
            'timestamps'    => [
                'last_start'    => isset($item['last_start_at']) ? $item['last_start_at'] : null,
                'last_finished' => isset($item['last_finished_at']) ? $item['last_finished_at'] : null,
                'created'       => $item['created_at'],
                'updated'       => $item['updated_at'],
            ],
            'icon'          =>  isset($item['icon']) ? $item['icon'] : '',
            'url'           =>  isset($item['url'])? $item['url'] : ''
        ];

        if (isset($item['sequence'])) {
            $return['sequence'] = (new ActionSequenceTransformer())->transform($item['sequence']);
        }

        return $return;
    }
}