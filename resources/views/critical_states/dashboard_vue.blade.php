<template id="criticalstates-widget-template">
    <div v-if="criticalstates.length" id="criticalstates-pulldown-slider-wrapper">
        <div class="panel panel-danger pulldown-slider closed" id="criticalstates-pulldown-slider">
            <div class="panel-body bg-danger">
                <div class="text-center" v-for="cs in criticalstates" :class="criticalstateClasses">
                    <span v-show="cs.belongs.type == 'PhyiscalSensor'">@choice('components.physical_sensors', 1)</span>
                    <span v-show="cs.belongs.type == 'LogicalSensor'">@choice('components.logical_sensors', 1)</span>
                    <span v-show="cs.belongs.type == 'Contolunit'">@choice('components.controlunit', 1)</span>
                    <span v-show="cs.belongs.type == 'Terrarium'">@choice('components.terrarium', 1)</span>
                    <span class="material-icons">@{{ cs.belongs.object.icon }}</span>
                    <strong><a href="@{{ cs.belongs.object.url }}">@{{ cs.belongs.object.name }}</a></strong>
                    <span v-show="cs.belongs.object.type == 'humidity_percent'">(@lang('labels.humidity_percent'))</span>
                    <span v-show="cs.belongs.object.type == 'temperature_celsius'">(@lang('labels.temperature_celsius'))</span>
                    @lang('labels.since') <strong>@{{ cs.timestamps.created }}</strong>
                </div>
            </div>
        </div>
        <div class="text-center" id="criticalstates-pulldown-button">
            <a class="btn btn-danger btn-sm" onClick="document.getElementById('criticalstates-pulldown-slider').classList.toggle('closed');">
                <i class="material-icons">expand_more</i>
                @{{ criticalstates.length }} @lang('labels.criticalstates')
                <i class="material-icons">expand_more</i>
            </a>
        </div>
    </div>
</template>