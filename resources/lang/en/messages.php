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
        'humidity_percent' => [
            'UNKNOWN' => 'Critical: The sensor :logical_sensor reports a humidity of :humidity_percent%C.',
            'LOWERLIMIT_DECEEDED' => 'Critical: The sensor :logical_sensor reports a too low humidity of :humidity_percent%C.',
            'UPPERLIMIT_EXCEEDED' => 'Critical: The sensor :logical_sensor reports a too high humidity of :humidity_percent%C.'
        ],
        'temperature_celsius' => [
            'UNKNOWN' => 'Critical: The sensor :logical_sensor reports a temperature of :temperature_celsius°C.',
            'LOWERLIMIT_DECEEDED' => 'Critical: The sensor :logical_sensor reports a too low temperature of :temperature_celsius°C.',
            'UPPERLIMIT_EXCEEDED' => 'Critical: The sensor :logical_sensor reports a too high temperature of :temperature_celsius°C.'
        ]
    ],
    'critical_state_recovery_notification_logical_sensors' => [
        'humidity_percent' => [
            'UNKNOWN' => 'OK: The sensor :logical_sensor reports a humidity of :humidity_percent%C.',
            'LOWERLIMIT_DECEEDED' => 'OK: The sensor :logical_sensor reports a humidity of :humidity_percent%C.',
            'UPPERLIMIT_EXCEEDED' => 'OK: The sensor :logical_sensor reports a humidity of :humidity_percent%C.'
        ],
        'temperature_celsius' => [
            'UNKNOWN' => 'OK: The sensor :logical_sensor reports a temperature of :temperature_celsius°C.',
            'LOWERLIMIT_DECEEDED' => 'OK: The sensor :logical_sensor reports a humidity of :humidity_percent%C.',
            'UPPERLIMIT_EXCEEDED' => 'OK: The sensor :logical_sensor reports a humidity of :humidity_percent%C.'
        ]
    ],
    'critical_state_notification_controlunits' => [
        'UNKNOWN' => 'Critical: The controlunit :controlunit is in an unknown state.',
        'HEARTBEAT_CRITICAL' => 'Critical: The controlunit :controlunit is not sending data.',
        'TIME_DIFF_CRITICAL' => 'Critical: The controlunit :controlunit has a too large time difference.'
    ],
    'critical_state_recovery_notification_controlunits' => [
        'UNKNOWN' => 'OK: The controlunit :controlunit is no longer in an unknown state.',
        'HEARTBEAT_CRITICAL' => 'OK: The controlunit :controlunit is sending data again.',
        'TIME_DIFF_CRITICAL' => 'OK: The controlunit :controlunit has an acceptable time difference again.'
    ],

    'daily' => [
        'intro' => 'Daily reminders',
        'feedings_due'  =>  'Feedings due:',
        'weighings_due' =>  'Weighings due:'
    ],

    'own_token_expires' => 'Token \':name\' expires in :days days.',

    'suggestions' => [
        'humidity_percent' => [
            'UPPERLIMIT_EXCEEDED' => 'Decrease humidity daily at :hour:00',
            'LOWERLIMIT_DECEEDED' => 'Increase humidity daily at :hour:00',
            'UNKNOWN' => 'Regulate humidity daily at :hour:00',
        ],
        'temperature_celsius' => [
            'UPPERLIMIT_EXCEEDED' => 'Decrease temperature daily at :hour:00',
            'LOWERLIMIT_DECEEDED' => 'Increase temperature daily at :hour:00',
            'UNKNOWN' => 'Regulate temperature daily at :hour:00'
        ]
    ]
];