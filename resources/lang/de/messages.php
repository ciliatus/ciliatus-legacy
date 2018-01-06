<?php

return [
    'logical_sensor_thresholds' => [
        'copy_warning' => 'Alle preexistenten Grenzwerte des Zielsensors werden entfernt.'
    ],

    'users' => [
        'setup_telegram_ok' =>  'Telegram ist eingerichtet.',
        'setup_telegram_err' =>  'Telegram ist noch nicht eingerichtet.',
        'setup_telegram_description' => 'Bitte öffnen Sie Telegram in Ihrem <a href="https://web.telegram.org/#/im?p=@' . env('TELEGRAM_BOT_NAME') . '">Browser</a> oder auf ihrem Smartphone und kontaktieren Sie <b>@' . env('TELEGRAM_BOT_NAME') . '</b> mit untenstehendem Aktivierungscode.'
    ],

    'critical_state_generic' => 'Kritisch: :critical_state',

    'critical_state_notification_logical_sensors' => [
        'humidity_percent' => 'Kritisch: Der Sensor :logical_sensor meldet eine Feuchtigkeit von :humidity_percent%.',
        'temperature_celsius' => 'Kritisch: Der Sensor :terrarium meldet eine Temperatur von :temperature_celsius°C.'
    ],
    'critical_state_recovery_notification_logical_sensors' => [
        'humidity_percent' => 'OK: Der Sensor :logical_sensor meldet eine Feuchtigkeit von :humidity_percent%.',
        'temperature_celsius' => 'OK: Der Sensor :terrarium meldet eine Temperatur von :temperature_celsius°C.'
    ],
    'critical_state_notification_controlunits' => 'Kritisch: Die Steuereinheit :controlunit sendet keine Daten.',
    'critical_state_recovery_notification_controlunit' => 'OK: Die Steuereinheit :controlunit sendet wieder Daten.',

    'daily' => [
        'intro' => 'Tägliche Erinnerungen',
        'feedings_due'  =>  'Fällige Fütterungen:',
        'weighings_due' =>  'Fällige Wiegungen:'
    ],

    'own_token_expires' => 'Token \':name\' läuft in :days Tagen ab.',

    'suggestions' => [
        'humidity_percent' => 'Feuchtigkeit regulieren täglich um :hour Uhr',
        'temperature_celsius' => 'Temperatur regulieren täglich um :hour Uhr'
    ]
];