<?php

return [
    'logical_sensor_thresholds' => [
        'copy_warning' => 'Alle preexistenten Grenzwerte des Zielsensors werden entfernt.'
    ],

    'users' => [
        'setup_telegram_ok' =>  'Telegram ist eingerichtet.',
        'setup_telegram_err' =>  'Telegram ist noch nicht eingerichtet.',
        'setup_telegram_description' => 'Bitte öffnen Sie Telegram in Ihrem <a href="https://web.telegram.org/#/im?p=@' . env('TELEGRAM_BOT_NAME') . '">Browser</a> oder auf ihrem Smartphone und kontaktieren Sie <b>@' . env('TELEGRAM_BOT_NAME') . '</b> mit untenstehendem Aktivierungscode.'
    ]
];