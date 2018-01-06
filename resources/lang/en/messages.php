<?php

return [
    'logical_sensor_thresholds' => [
        'copy_warning' => 'All existing thresholds associated with the target sensor will be deleted.'
    ],

    'users' => [
        'setup_telegram_ok' =>  'Telegram is set up.',
        'setup_telegram_err' =>  'Telegram has not yet been set up.',
        'setup_telegram_description' => 'Please point your browser to <a href="https://web.telegram.org/#/im?p=@' . env('TELEGRAM_BOT_NAME') . '">Telegram Web</a> or use your smartphone to contact <b>@' . env('TELEGRAM_BOT_NAME') . '</b> with your verification code below.'
    ],

    'critical_state_generic' => 'Critical: :critical_state',

    'critical_state_notification_logical_sensors' => [
        'humidity_percent' => 'Critical: The sensor :logical_sensor reports a humidity of :humidity_percent%C.',
        'temperature_celsius' => 'Critical: The sensor :logical_sensor reports a temperature of :temperature_celsius°C.'
    ],
    'critical_state_recovery_notification_logical_sensors' => [
        'humidity_percent' => 'OK: The sensor :logical_sensor reports a humidity of :humidity_percent%C.',
        'temperature_celsius' => 'OK: The sensor :logical_sensor reports a temperature of :temperature_celsius°C.'
    ],
    'critical_state_notification_controlunits' => 'Critical: The controlunit :controlunit is not sending data.',
    'critical_state_recovery_notification_controlunit' => 'OK: The controlunit :controlunit ist sending data again.',

    'daily' => [
        'intro' => 'Daily reminders',
        'feedings_due'  =>  'Feedings due:',
        'weighings_due' =>  'Weighings due:'
    ],

    'own_token_expires' => 'Token \':name\' expires in :days days.',

    'suggestions' => [
        'humidity_percent' => 'Rgulate humidity daily at :hour o\'clock.',
        'temperature_celsius' => 'Rgulate temperature daily at :hour o\'clock.'
    ]
];