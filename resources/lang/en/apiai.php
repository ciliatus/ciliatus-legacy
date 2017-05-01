<?php

return [
    'errors' => [
        'animal_multiple_options'=>'I know multiple animals named :display_name.',
        'animal_no_terrarium'   => ':display_name does not have a terrarium.',
        'animal_not_found'      => 'I don\'t know :display_name.',
        'could_not_send_request'=> 'Request could not be parsed.',
        'query_not_understood'  => 'Ciliatus could not understand your request.'
    ],

    'fulfillment' => [
        'animal_health'         => 'At :display_name it\'s :temperature°C and :humidity% humidity.',
        'feedings_today_none'   => 'No outstanding feedings today.',
        'feedings_today_list'   => 'You need to feed :type to: :display_names today.',
        'animal_next_feeding_none'=>':display_name has no outstanding feeding schedules.',
        'animal_next_feeding_list'=>':display_name gets :type :time'
    ]
];