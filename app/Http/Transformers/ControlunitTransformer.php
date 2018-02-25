<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 23.07.2016
 * Time: 15:18
 */

namespace App\Http\Transformers;

/**
 * Class ControlunitTransformer
 * @package App\Http\Transformers
 */
class ControlunitTransformer extends Transformer
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
            'client_server_time_diff_seconds' => $item['client_server_time_diff_seconds'],
            'software_version' => isset($item['software_version']) ? $item['software_version'] : null,
            'heartbeat_critical' => isset($item['heartbeat_critical']) ? $item['heartbeat_critical'] : null,
            'state_ok' => isset($item['state_ok']) ? $item['state_ok'] : null,
            'timestamps' => $this->parseTimestamps($item, ['heartbeat_at' => 'last_heartbeat']),
        ];

        $return = $this->addCiliatusSpecificFields($return, $item);

        return $return;
    }
}