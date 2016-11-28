<?php

return [
    'logical_sensor_thresholds' => [
        'copy_warning' => 'All existing thresholds associated with the target sensor will be deleted.'
    ],

    'users' => [
        'setup_telegram_ok' =>  'Telegram is set up.',
        'setup_telegram_err' =>  'Telegram has not yet been set up.',
        'setup_telegram_description' => 'Please point your browser to <a href="https://web.telegram.org/#/im?p=@' . env('TELEGRAM_BOT_NAME') . '">Telegram Web</a> or use your smartphone to contact <b>@' . env('TELEGRAM_BOT_NAME') . '</b> with your verification code below.'
    ]
];