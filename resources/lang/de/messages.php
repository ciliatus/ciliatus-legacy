<?php

return [
    'logical_sensor_thresholds' => [
        'copy_warning' => 'Alle preexistenten Schwellenwerte des Zielsensors werden entfernt.'
    ],

    'users' => [
        'setup_telegram_ok' =>  'Telegram ist eingerichtet.',
        'setup_telegram_err' =>  'Telegram ist noch nicht eingerichtet.',
        'setup_telegram_description' => 'Bitte öffne Telegram in Deinem <a href="https://web.telegram.org/#/im?p=@:bot_name">Browser</a> oder auf Deinem Smartphone und kontaktiere <b>@:bot_name</b> mit untenstehendem Aktivierungscode.'
    ],

    'critical_state_generic' => 'Kritisch: :critical_state',

    'critical_state_notification_logical_sensors' => [
        'humidity_percent' => [
            'UNKNOWN' => 'Kritisch: Der Sensor :logical_sensor meldet eine Feuchtigkeit von :humidity_percent%.',
            'LOWERLIMIT_DECEEDED' => 'Kritisch: Der Sensor :logical_sensor meldet eine zu niedrige Feuchtigkeit von :humidity_percent%.',
            'UPPERLIMIT_EXCEEDED' => 'Kritisch: Der Sensor :logical_sensor meldet eine zu hohe Feuchtigkeit von :humidity_percent%.'
        ],
        'temperature_celsius' => [
            'UNKNOWN' => 'Kritisch: Der Sensor :logical_sensor meldet eine Temperatur von :temperature_celsius°C.',
            'LOWERLIMIT_DECEEDED' => 'Kritisch: Der Sensor :logical_sensor meldet eine zu niedrige Temperatur von :temperature_celsius°C.',
            'UPPERLIMIT_EXCEEDED' => 'Kritisch: Der Sensor :logical_sensor meldet eine zu hohe Temperatur von :temperature_celsius°C.'
        ]
    ],
    'critical_state_recovery_notification_logical_sensors' => [
        'humidity_percent' => [
            'UNKNOWN' => 'OK: Der Sensor :logical_sensor meldet eine Feuchtigkeit von :humidity_percent%.',
            'LOWERLIMIT_DECEEDED' => 'OK: Der Sensor :logical_sensor meldet eine Feuchtigkeit von :humidity_percent%.',
            'UPPERLIMIT_EXCEEDED' => 'OK: Der Sensor :logical_sensor meldet eine Feuchtigkeit von :humidity_percent%.'
        ],
        'temperature_celsius' => [
            'UNKNOWN' => 'OK: Der Sensor :terrarium meldet eine Temperatur von :temperature_celsius°C.',
            'LOWERLIMIT_DECEEDED' => 'OK: Der Sensor :terrarium meldet eine Temperatur von :temperature_celsius°C.',
            'UPPERLIMIT_EXCEEDED' => 'OK: Der Sensor :terrarium meldet eine Temperatur von :temperature_celsius°C.'
        ]
    ],
    'critical_state_notification_controlunits' => [
        'UNKNOWN' => 'Kritisch: Die Steuereinheit :controlunit befindet sich in einem unbekannten Zustand.',
        'HEARTBEAT_CRITICAL' => 'Kritisch: Die Steuereinheit :controlunit sendet keine Daten.',
        'TIME_DIFF_CRITICAL' => 'Kritisch: Die Steuereinheit :controlunit hat eine zu hohe Zeitdifferenz.'
    ],
    'critical_state_recovery_notification_controlunits' => [
        'UNKNOWN' => 'OK: Die Steuereinheit :controlunit ist nicht mehr in einem unbekannten Zustand.',
        'HEARTBEAT_CRITICAL' => 'OK: Die Steuereinheit :controlunit sendet wieder Daten.',
        'TIME_DIFF_CRITICAL' => 'OK: Die Steuereinheit :controlunit hat wieder eine akzeptable Zeitdifferenz.'
    ],

    'daily' => [
        'intro' => 'Tägliche Erinnerungen',
        'feedings_due'  =>  'Fällige Fütterungen:',
        'weighings_due' =>  'Fälliges Wiegen:'
    ],

    'own_token_expires' => 'Token \':name\' läuft in :days Tagen ab.',

    'suggestions' => [
        'humidity_percent' => [
            'UPPERLIMIT_EXCEEDED' => 'Feuchtigkeit reduzieren täglich um :hour',
            'LOWERLIMIT_DECEEDED' => 'Feuchtigkeit erhöhen täglich um :hour',
            'UNKNOWN' => 'Feuchtigkeit regulieren täglich um :hour'
        ],
        'temperature_celsius' => [
            'UPPERLIMIT_EXCEEDED' => 'Feuchtigkeit reduzieren täglich um :hour',
            'LOWERLIMIT_DECEEDED' => 'Temperatur erhöhen täglich um :hour',
            'UNKNOWN' => 'Temperatur regulieren täglich um :hour'
        ]
    ],

    'cards' => [
        'no_feedings' => 'Keine Fütterungen',
        'no_weight' => 'Kein Gewicht',
        'no_humidity' => 'Keine Feuchtigkeitsdaten',
        'no_temperature' => 'Keine Temperaturdaten',
        'data_too_old' => 'Daten zu alt'
    ],

    'warnings' => [
        'physical_sensor_belonging' => 'Beim Ändern der Zugehörigkeit eines physischen Sensors werden alle Messerwerte '.
            'dieses Sensors dem neuen Objekt zugeordnet. In der Regel sollte man stattdessen einen neuen physischen ' .
            'Sensor anlegen'
    ]
];