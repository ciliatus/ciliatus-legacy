<?php

return [
    'errors' => [
        'animal_multiple_options'=>'Ich kenne mehrere Tiere namens :display_name.',
        'animal_no_terrarium'   => ':display_name scheint kein Terrarium zu haben.',
        'animal_not_found'      => 'Ich kenne :display_name leider nicht.',
        'could_not_send_request'=> 'Anfrage konnte nicht versendet werden.',
        'query_not_understood'  => 'Ciliatus hat deine Frage nicht verstanden.'
    ],

    'fulfillment' => [
        'animal_health'         => 'Bei :display_name hat es :temperature°C und :humidity% Luftfeuchtigkeit.',
        'feedings_today_none'   => 'Heute muss niemand gefüttert werden.',
        'feedings_today_list'   => ':type bekommen heute: :display_names.',
        'animal_next_feeding_none'=>':display_name hat keine geplanten Fütterungen.',
        'animal_next_feeding_list'=>':display_name bekommt :time :type'
    ]
];