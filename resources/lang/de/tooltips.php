<?php

return [
    'ctrltoselect'          =>  'Strg+Klick zum selektieren',
    'active'                =>  'Aktiv',
    'showondefaultdashboard'=>  'Auf Default Dashboard anzeigen',
    'autoirrigation'        =>  'Automatische Bewässerung',
    'sendnotificationsfor'  =>  'Benachrichtigungen versenden für',
    'loadandrendergraph'    =>  'Daten werden ermittelt und Graph wird gerendert',
    'disables_option'       =>  'Deaktiviert ":option"',
    'phone_number'          =>  'Mobilnummer',
    'contact_bot'           =>  'Den Bot kontaktieren',
    'wait_confirmation'     =>  'Auf Bestätigung warten',
    'set_state_to'          =>  'Zustand von <b>:target</b> auf <b>:state</b> ändern für <b>:minutes Minuten</b>',
    'start_after_started'   =>  'Startet wenn Schritt <b>:id</b> gestartet wurde',
    'start_after_finished'  =>  'Startet wenn Schritt <b>:id</b> beendet wurde',
    'sendnotifications'     =>  'Benachrichtigungen versenden',
    'no_schedules'          =>  'Keine Zeitpläne',
    'runonce'               =>  'Einmalig',
    'heartbeat_critical'    =>  'Heartbeat ist kritisch!',
    'copy_thresholds_warning'=> 'Alle existierenden Grenzwerte des Zielsensors werden entfernt.',
    'animal_feeding_schedule_matrix' => 'Diese Matrix enthält alle definierten Fütterungspläne. Die Zahl in einer Spalte stellt das Intervall dar.',
    'animal_weighing_schedule_matrix' => 'Diese Matrix enthält alle definierten Wiegepläne. Die Zahl in einer Spalte stellt das Intervall gefolgt vom nächsten Fälligkeitsdatum dar.',
    'done'                  =>  'Erledigt',
    'skip'                  =>  'Überspringen',
    'material_icons_list'   =>  'Die komplette Symbolliste ist unter <a href="https://material.io/icons/">material.io</a> einsehbar.',
    'no_data'               =>  'Keine Daten.',
    'connecting_to_server'  =>  'Verbindung zum Ciliatus Server wird hergestellt. Sollte dies länger als einige Sekunden dauern, überprüfen Sie bitte Ihre Internetverbindung.',
    'generic_components' => [
        'about'                 => 'Generische Komponenten sind Komponenten eines benutzerdefinierten Typs.',
        'type_about'            => 'Generische Komponententypen definieren Name, Eigenschaften und mögliche Zustände für generische Komponenten. Sie dienen als Vorlage beim Erstellen einer neuen generischen Komponente.',
        'property_templates'    => 'Definiert die Eigenschaften eines generischen Komponententyps. Beim Erstellen einer neuen Komponente diesen Typs wird man aufgefordert, diese Eigenschaften auszufüllen.',
        'state_templates'       => 'Definiert mögliche Zustände die eine Komponente diesen Typs haben kann. Beim Erstellen einer Aktionssequenz kann man aus den hier definierten Zuständen den gewünschten Zustand auswählen.',
        'type_delete_warning'   => 'Beim Löschen eines Komponententyps werden <strong>alle Komponenten dieses Typs</strong> gelöscht.',
    ],
    'minimum_timeout_minutes'=> 'Definiert die Dauer der minimalen Pause, bevor die Aktionssequenz durch diesen Auslöser nach einem Durchlauf erneut gestartet werden kann.',
    'reference_value' => 'Der Wert, mit dem der Sensorwert verglichen werden soll.',
    'reference_value_duration_threshold_minutes' => 'Dauer in Minuten, die der Sensorwert den Grenzwert unter/überschritten haben muss, bevor die Aktionssequenz ausgelöst wird.',
    'emergency_stop'    =>  'Hält sofort alle Aktionssequenzen an und verhindert das Starten neuer Aktionssequenzen bis der Notaus aufgehoben wird.',
    'emergency_resume'  =>  'Hebt den Notaus auf und erlaubt den Start von Aktionssequenzen.'
];