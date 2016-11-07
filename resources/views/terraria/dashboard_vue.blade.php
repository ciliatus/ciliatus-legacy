<template id="terraria-widget-template">
    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 col-xs-12" v-for="terrarium in terraria">
        <div class="x_panel" v-bind:class="{ 'x_panel-danger': !terrarium.state_ok }">

            <div class="x_title">
                <h2>
                    <i class="material-icons">video_label</i>
                    <a href="/terraria/@{{ terrarium.id }}">@{{ terrarium.display_name }}</a>
                    <i v-show="!terrarium.state_ok" class="material-icons">priority_high</i>
                </h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="material-icons">expand_less</i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="material-icons">settings</i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="/terraria/@{{ terrarium.id }}/edit"><i class="material-icons">mode_edit</i> @lang('menu.edit')</a>
                            </li>
                            <li>
                                <a href="/terraria/@{{ terrarium.id }}/delete"><i class="material-icons">delete</i> @lang('menu.delete')</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="row">
                    <div class="col-xs-12">
                        <div v-for="animal in terrarium.animals">
                            <span class="material-icons">pets</span><a href="/animals/@{{ animal.id }}">@{{ animal.display_name }}</a> <i>@{{ animal.common_name }}</i>
                        </div>
                    </div>
                </div>

                <div class="row weather-days">
                    <div class="col-xs-6">
                        <div class="daily-weather">
                            <h2 class="day">
                                @lang('labels.temperature')
                            </h2>
                            <h3 class="terrarium-widget-temp" v-bind:class="{ 'text-danger': !terrarium.temperature_ok }">
                                @{{ terrarium.cooked_temperature_celsius }} °C
                            </h3>
                            <div class="widget-sparkline dashboard-widget-sparkline-temp">
                                <inline-graph :parentid="terrarium.id" graphtype="temperature_celsius" type="line" :options="{'fill': '#FFAAAA', width: '100%', height:'50px', min: 10, max: 40}" :source="'/api/v1/terraria/'+terrarium.id+'/sensorreadingsByType/temperature_celsius'"></inline-graph>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="daily-weather">
                            <h2 class="day">
                                @lang('labels.humidity')
                            </h2>
                            <h3 class="terrarium-widget-humidity" v-bind:class="{ 'text-danger': !terrarium.humidity_ok }">
                                @{{ terrarium.cooked_humidity_percent }} %
                            </h3>
                            <div class="widget-sparkline dashboard-widget-sparkline-humidity">
                                <inline-graph :parentid="terrarium.id" graphtype="humidity_percent" type="line" :options="{'fill': '#AAAAFF', width: '100%', height:'50px', min: 1, max: 99}" :source="'/api/v1/terraria/'+terrarium.id+'/sensorreadingsByType/humidity_percent'"></inline-graph>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

        </div>
    </div>
</template>