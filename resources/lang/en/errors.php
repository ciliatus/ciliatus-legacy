<?php

return [
    'retrievegraphdata'     =>  'Could not retrieve graph data.',

    'frontend' => [
        'generic'                   => 'An error occured. Check the console for details.',
        'no_recording_capability'   => 'The browser doesn\'t seem to support voice control.',
        'no_target_object'          => 'Target not found',
        'auth'  =>  [
            'title'  =>  'Login failed',
            'failed' => 'E-Mail address and password do not match.'
        ],
    ],

    'codes' => [
        'common' => [
            '101' => 'Object not found.',
            '102' => 'Related object not found: :related_object.',
            '103' => 'Could not parse timestamp :timestamp.',
            '104' => 'Missing fields: :missing_fields',
            '105' => 'Class not found.',
            '106' => 'Invalid UUID: :uuid'
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
                '201' => 'Unknown comparison.'
            ],

            // 16x AnimalController
            '16' => [],

            // 17x AnimalFeedingEventController
            '17' => [],

            // 18x AnimalFeedingSchedulePropertyController
            '18' => [
                '201' => 'A feeding schedule for this type of food already exists for this animal.',
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
                '201' => 'No file to upload.',
                '202' => 'File is too big. Maximum: :max_size MB.'
            ],

            // 21x CustomComponentController
            '21' => [
                '201' => 'Generic component is corrupted.'
            ],

            // 22x CustomComponentTypeController
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
                '201' => 'Raw value is outside of the sensor\'s valid range.',
                '202' => 'There already is a sensor reading of this Logical Sensor withing the reading group.',
            ],

            // 2Ax SystemController
            '2A' => [],

            // 2Bx TerrariumController
            '2B' => [
                '201' => 'Unknown error while generating Action Sequence.'
            ],

            // 2Cx UserController
            '2C' => [
                '201' => 'Username already taken.',
                '202' => 'E-Mail address already taken.',
                '203' => 'The passwords don\'t match.',
                '204' => 'No password set.'
            ],

            // 2Dx UserSettingController
            '2D' => [],

            // 2Ex ValveController
            '2E' => [],
        ]
    ]
];