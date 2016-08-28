<template id="terraria-widget-template">
    <div v-for="terrarium in terraria">
        <div class="x_panel">

            <div class="x_title">
                <h2><i class="material-icons">video_label</i><a href="/terraria/@{{ terrarium.id }}">@{{ terrarium.display_name }}</a></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="material-icons">expand_less</i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="material-icons">settings</i></a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href=""><i class="material-icons">mode_edit</i> @lang('menu.edit')</a>
                            </li>
                            <li>
                                <a href=""><i class="material-icons">delete</i> @lang('menu.delete')</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <div class="row">
                    <div class="col-xs-12">

                    </div>
                </div>

                <div class="row weather-days">
                    <div class="col-sm-6 col-xs-12 col-md-6 col-lg-6 col-xl-6">
                        <div class="daily-weather">
                            <h2 class="day">@lang('labels.temperature')</h2>
                            <h3 class="terrarium-widget-temp">@{{ terrarium.cooked_temperature_celsius }} °C</h3>
                            <div class="widget-sparkline dashboard-widget-sparkline-temp">
                                <inline-graph :parentid="terrarium.id" graphtype="temperature_celsius" type="line" :options="{'fill': '#FFAAAA', width: '100%', height:'50px', min: 10, max: 40}" :source="'/api/v1/terraria/'+terrarium.id+'/sensorreadingsByType/temperature_celsius'"></inline-graph>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12 col-md-6 col-lg-6 col-xl-6">
                        <div class="daily-weather">
                            <h2 class="day">@lang('labels.humidity')</h2>
                            <h3 class="terrarium-widget-humidity">@{{ terrarium.cooked_humidity_percent }} %</h3>
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