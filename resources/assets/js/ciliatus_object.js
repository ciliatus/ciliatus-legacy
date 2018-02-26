export default class CiliatusObject {

    constructor(ciliatus, type, id, data, include) {
        this.ciliatus = ciliatus;
        this.type = type;
        this.id = id;
        this.data = data;
        this.include = include ? include : [];
        this.max_age_seconds = 60;
        this.api_url = global.apiUrl;
        this.init_done = false;
        this.refreshing = false;

        this.__verifyId();

        if (!this.data) {
            this.refresh();
        }
        else {
            this.last_change = 0;
            this.last_refresh = Date.now();
            this.last_persist = 0
        }

        let that = this;
        let event_name;
        
        if  (event_name = this.__updateEventName()) {
            window
                .echo
                .private('dashboard-updates')
                .listen(event_name, function (e) {
                    if (e.id === that.id) {
                        that.refresh();
                    }
                });
        }

        if (event_name = this.__deleteEventName()) {
            window
                .echo
                .private('dashboard-updates')
                .listen(event_name, function (e) {
                    if (e.id === that.id) {
                        that.removeFromStore();
                    }
                });
        }
    }

    refresh (force) {
        if (
            (!force && !(this.last_change > this.last_refresh))
            || this.last_change === undefined || this.last_refresh === undefined
        ) {
            this.refreshing = true;
            jQuery.ajax({
                context: this,
                url: this.url(),
                method: 'GET',
                success: this.handleApiResult
            })
        }
        else {
            window.console.log(
                'Not updating ' + this.type + ' ' + this.id + '. ' +
                'Last change (' + this.last_change + ') is > last refresh (' + this.last_change + ')'
            );
        }
    }

    removeFromStore () {
        this.ciliatus.removeObject(this);
    }

    url () {
        let includes = this.include.map(i => 'with[]=' + i).join('&');
        return this.api_url + '/' + this.type + '/' + this.id + '?' + includes;
    }

    handleApiResult (result) {
        this.data = result['data'];
        this.last_change = 0;
        this.last_refresh = Date.now();
        this.last_persist = 0;

        this.__verifyId();

        this.refreshing = false;

        if (!this.init_done) {
            window.echo.private('dashboard-updates')
                .listen(this.data.class + 'Updated', (e) => {
                    this.refresh()
                }).listen(this.data.class + 'Deleted', (e) => {
                    this.data = null;
                });

            this.init_done = true;
        }
    }

    persist () {
        let that = this;
        $.ajax({
            url: that.url(),
            data: that.data,
            method: 'PUT',
            success: function (data) {
                console.log('OK');
            },
            error: function (error) {
                console.log(JSON.stringify(error));
            }
        });
        this.last_persist = Date.now();
    }

    __verifyId () {
        if (this.data && this.data.id !== this.id) {
            console.log('----------------------------');
            console.log('CiliatusModel ' + this.type + ' mismatched ID');
            console.log(this.id + ' <> ' + this.data.id);
            console.log(this);
            return false;
        }

        return true;
    }

    __deleteEventName() {
        let event_names = {
            action_sequences: 'ActionSequenceDeleted',
            action_sequence_intentions: 'ActionSequenceIntentionDeleted',
            action_sequence_schedules: 'ActionSequenceScheduleDeleted',
            action_sequence_triggers: 'ActionSequenceTriggerDeleted',
            animals: 'AnimalDeleted',
            animal_weighings: 'AnimalWeighingDeleted',
            animal_feedings: 'AnimalFeedingDeleted',
            animal_weighing_schedules: 'AnimalWeighingScheduleDeleted',
            animal_feeding_schedules: 'AnimalFeedingScheduleDeleted',
            biography_entries: 'BiographyEntryDeleted',
            caresheets: 'CaresheetDeleted',
            controlunits: 'ControlunitDeleted',
            files: 'FileDeleted',
            generic_components: 'GenericComponentDeleted',
            logical_sensors: 'LogicalSensorDeleted',
            logical_sensor_thresholds: 'LogicalSensorThresholdsDeleted',
            physical_sensors: 'PhysicalSensorDeleted',
            pumps: 'PumpDeleted',
            suggestions: 'SuggestionDeleted',
            terraria: 'TerrariumDeleted',
            users: 'UserDeleted',
            valves: 'ValveDeleted',
        };

        return event_names[this.type];
    }

    __updateEventName() {
        let event_names = {
            action_sequences: 'ActionSequenceUpdated',
            action_sequence_intentions: 'ActionSequenceIntentionUpdated',
            action_sequence_schedules: 'ActionSequenceScheduleUpdated',
            action_sequence_triggers: 'ActionSequenceTriggerUpdated',
            animals: 'AnimalUpdated',
            animal_weighings: 'AnimalWeighingUpdated',
            animal_feedings: 'AnimalFeedingUpdated',
            animal_weighing_schedules: 'AnimalWeighingScheduleUpdated',
            animal_feeding_schedules: 'AnimalFeedingScheduleUpdated',
            biography_entries: 'BiographyEntryUpdated',
            caresheets: 'CaresheetUpdated',
            controlunits: 'ControlunitUpdated',
            files: 'FileUpdated',
            generic_components: 'GenericComponentUpdated',
            logical_sensors: 'LogicalSensorUpdated',
            logical_sensor_thresholds: 'LogicalSensorThresholdsUpdated',
            physical_sensors: 'PhysicalSensorUpdated',
            pumps: 'PumpUpdated',
            suggestions: 'SuggestionUpdated',
            terraria: 'TerrariumUpdated',
            users: 'UserUpdated',
            valves: 'ValveUpdated',
        };

        return event_names[this.type];
    }
}