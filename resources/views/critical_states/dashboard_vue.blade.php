<template id="criticalstates-widget-template">
    <div class="panel panel-danger" v-if="criticalstates.length">
        <div class="panel-body">
            <div v-for="cs in criticalstates" :class="criticalstateClasses">
                <span class="material-icons">@{{ cs.belongs.object.icon }}</span>  <a href="@{{ cs.belongs.object.url }}">@{{ cs.belongs.object.name }}</a>
            </div>
        </div>
    </div>
</template>