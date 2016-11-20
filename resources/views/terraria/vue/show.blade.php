<template id="terraria-widget-template">
    <div>
        <div :class="wrapperClasses" v-for="terrarium in terraria">
            <div class="card">
                <div class="card-image waves-effect waves-block waves-light terrarium-card-image" v-bind:style="'background-image: url(\'' + terrarium.default_background_filepath + '\');'">
                    <div>
                        <inline-graph :parentid="terrarium.id" graphtype="humidity_percent" type="line" :options="{'fill': null, 'strokeWidth': '3', 'stroke': '#2196f3', width: '100%', height:'60px', min: 1, max: 99}" :source="'/api/v1/terraria/'+terrarium.id+'/sensorreadingsByType/humidity_percent'"></inline-graph>
                    </div>

                    <div style="position: relative; top: -60px">
                        <inline-graph :parentid="terrarium.id" graphtype="temperature_celsius" type="line" :options="{'fill': null, 'strokeWidth': '3', 'stroke': '#b71c1c', width: '100%', height:'60px', min: 10, max: 40}" :source="'/api/v1/terraria/'+terrarium.id+'/sensorreadingsByType/temperature_celsius'"></inline-graph>
                    </div>
                </div>
                <div class="card-content">
                    <span class="card-title activator grey-text text-darken-4 truncate">
                        <span>@{{ terrarium.display_name }}</span>
                        <i class="material-icons right">more_vert</i>
                    </span>
                    <p>
                        <span v-bind:class="{ 'red-text': !terrarium.temperature_ok, 'darken-3': !terrarium.temperature_ok }">@lang('labels.temperature'): @{{ terrarium.cooked_temperature_celsius }}°C</span><br />
                        <span v-bind:class="{ 'red-text': !terrarium.humidity_ok, 'darken-3': !terrarium.humidity_ok }">@lang('labels.humidity'): @{{ terrarium.cooked_humidity_percent }}%</span>
                        <span v-show="!terrarium.heartbeat_ok" class="red-text darken-3">
                            <br />@lang('tooltips.heartbeat_critical')
                        </span>
                    </p>
                </div>

                <div class="card-action">
                    <a v-bind:href="'/terraria/' + terrarium.id">@lang('buttons.details')</a>
                </div>

                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4">@choice('components.animals', 2)<i class="material-icons right">close</i></span>
                    <p>
                        <div v-for="animal in terrarium.animals">
                            <a v-bind:href="'/animals/' + animal.id">@{{ animal.display_name }}</a> <i>@{{ animal.common_name }}</i>
                        </div>
                    </p>
                    <span class="card-title grey-text text-darken-4">@choice('components.action_sequence_schedules', 2)</span>
                    <p>
                    <div v-for="as in terrarium.action_sequences">
                        <span v-for="ass in as.schedules">
                            <a v-bind:href="'/action_sequences/' + as.id">@{{ as.name }}</a> <i>@lang('labels.starts_at') @{{ ass.timestamps.starts }}</i>
                        </span>
                    </div>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>