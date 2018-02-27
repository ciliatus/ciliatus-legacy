<?php

return [
    'retrievegraphdata'     =>  'Graphdaten konnten nicht ermittelt werden.',
    'auth'  =>  [
        'failed' => 'Authentifizierung fehlgeschlagen.'
    ],
    'frontend' => [
        'generic' => 'Ein Fehler ist aufgetreten. Details in der Konsole.',
        'no_recording_capability' => 'Der Browser scheint keine Sprachsteuerung zu unterstützen.'
    ],
    'codes' => [
        'common' => [
            '101' => 'Objekt konnte nicht gefunden werden.',
            '102' => 'Verwandtes Objekt konnte nicht gefunden werden: :related_object.',
            '103' => 'Zeitstempel konnte nicht geparst werden: :timestamp.',
            '104' => 'Fehlende Felder: :missing_fields',
            '105' => 'Klasse nicht gefunden.',
            '106' => 'Ungültige UUID: :uuid'
        ],
        'custom' => [
            // 11x - ActionController
            '11' => [],

            // 12x ActionSequenceController
            '12' => [],

            // 13x ActionSequenceIntentionController
            '13' => [],

            // 14x ActionSequenceScheduleController
            '14' => [],

            // 15x ActionSequenceTriggerController
            '15' => [
                '201' => 'Vergleichstyp wurde nicht gefunden.'
            ],

            // 16x AnimalController
            '16' => [],

            // 17x AnimalFeedingEventController
            '17' => [],

            // 18x AnimalFeedingSchedulePropertyController
            '18' => [
                '201' => 'Ein Fütterungsplan für diese Nahrungsart existiert bei diesem Tier bereits.',
            ],

            // 19x AnimalWeighingEventController
            '19' => [],

            // 1Ax AnimalWeighingSchedulePropertyController
            '1A' => [],

            // 1Bx ApiAiController
            '1B' => [],

            // 1Cx BiographyEntryEventController
            '1C' => [],

            // 1Dx ControlunitController
            '1D' => [],

            // 1Ex CriticalStateController
            '1E' => [],

            // 1Fx DashboardController
            '1F' => [],

            // 20x FileController
            '20' => [
                '201' => 'Keine Datei zum hochladen.',
                '202' => 'Datei ist zu groß. Maximum: :max_size MB.'
            ],

            // 21x GenericComponentController
            '21' => [
                '201' => 'Generische Komponente ist korrupt.'
            ],

            // 22x GenericComponentTypeController
            '22' => [],

            // 23x LogController
            '23' => [],

            // 24x LogicalSensorController
            '24' => [],

            // 25x LogicalSensorThresholdController
            '25' => [],

            // 26x PhysicalSensorController
            '26' => [],

            // 27x PropertyController
            '27' => [],

            // 28x PumpController
            '28' => [],

            // 29x SensorreadingController
            '29' => [
                '201' => 'Der Rohwert liegt außerhalb des Gültigkeitsbereichs.',
                '202' => 'Innerhalb dieser reading group gibt es bereits einen Wert für diesen Logischen Sensor.',
            ],

            // 2Ax SystemController
            '2A' => [],

            // 2Bx TerrariumController
            '2B' => [
                '201' => 'Beim Generieren der Aktionssequenz ist ein unbekannter Fehler aufgetreten.'
            ],

            // 2Cx UserController
            '2C' => [
                '201' => 'Der Benutzername ist bereits vergeben.',
                '202' => 'Die E-Mail Adresse ist bereits vergeben.',
                '203' => 'Die Passwörter sind unterschiedlich.',
                '204' => 'Es wurde kein Passwort vergeben.'
            ],

            // 2Dx UserSettingController
            '2D' => [],

            // 2Ex ValveController
            '2E' => [],
        ]
    ]
];