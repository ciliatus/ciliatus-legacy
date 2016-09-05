<template id="criticalstates-widget-template">
    <div v-if="criticalstates.length" id="criticalstates-pulldown-slider-wrapper">
        <div class="panel panel-danger pulldown-slider closed" id="criticalstates-pulldown-slider">
            <div class="panel-body bg-danger">
                <div class="text-center" v-for="cs in criticalstates" :class="criticalstateClasses">
                    <span class="material-icons">@{{ cs.belongs.object.icon }}</span> <strong><a href="@{{ cs.belongs.object.url }}">@{{ cs.belongs.object.name }}</a></strong>
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