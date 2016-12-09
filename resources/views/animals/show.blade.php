@extends('master')

@section('breadcrumbs')
    <a href="/animals" class="breadcrumb">@choice('components.animals', 2)</a>
    <a href="/animals/{{ $animal->id }}" class="breadcrumb">{{ $animal->display_name }}</a>
@stop

@section('content')

    <div class="col s12">
        <ul class="tabs z-depth-1">
            <li class="tab col s3"><a class="active" href="#tab_overview">@lang('labels.overview')</a></li>
            <li class="tab col s3"><a href="#tab_health">@lang('labels.health')</a></li>
            @if (!is_null($animal->terrarium))
                <li class="tab col s3"><a href="#tab_environment">@lang('labels.environment')</a></li>
            @endif
            <li class="tab col s3"><a target="_self" href="{{ url('animals/' . $animal->id . '/edit') }}">@lang('buttons.edit')</a></li>
        </ul>
    </div>
    <div id="tab_overview" class="col s12">
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l4">
                    <animals-widget animal-id="{{ $animal->id }}"
                                    :subscribe-add="false" :subscribe-delete="false"
                                    container-classes="row" wrapper-classes="col s12"></animals-widget>
                </div>
                <div class="col s12 m6 l4">
                    <files-widget source-filter="?filter[belongsTo_type]=Animal&filter[belongsTo_id]={{ $animal->id }}"
                                  belongs-to_type="Animal" belongs-to_id="{{ $animal->id }}"
                                  container-classes="row" wrapper-classes="col s12"></files-widget>
                </div>
            </div>
        </div>
    </div>

    <div id="tab_health" class="col s12">
        <div class="container">
            <div class="row">
                <div class="col s12 m6 l4">
                    <animal_feeding_schedules-widget animal-id="{{ $animal->id }}"></animal_feeding_schedules-widget>

                    <animal_feedings-widget animal-id="{{ $animal->id }}"></animal_feedings-widget>
                </div>

                <div class="col s12 m6 l4">
                    <animal_weighing_schedules-widget animal-id="{{ $animal->id }}"></animal_weighing_schedules-widget>

                    <animal_weighings-widget animal-id="{{ $animal->id }}"></animal_weighings-widget>
                </div>

                <!--
                <div class="col s12 m7 l8">
                    <div class="card">
                        <div class="card-content teal lighten-1 white-text">
                            count @lang('labels.measurement_count')
                        </div>

                        <div class="card-content">
                        <span class="card-title activator truncate">
                            @lang('labels.weight_history')
                            <i class="material-icons right">more_vert</i>
                        </span>
                            <p>
                            <div id="material-graph-animal-weight-{{ $animal->id }}"></div>
                            </p>
                        </div>

                        <div class="card-action">
                        </div>

                        <div class="card-reveal">
                            <span class="card-title grey-text text-darken-4"><i class="material-icons right">close</i></span>
                            <p>

                            </p>
                        </div>
                    </div>

                    <script>
                        document.addEventListener("DOMContentLoaded", function(event) {
                            google.charts.load('current', {packages: ['corechart', 'line']});
                            google.charts.setOnLoadCallback(drawTrendlines);

                            function drawTrendlines() {
                                var data = new google.visualization.DataTable();
                                data.addColumn('date', 'Time');
                                data.addColumn('number', 'Weight/g');

                                data.addRows([
                                    [new Date('2016-11-01'), 5], [new Date('2016-11-02'), 7], [new Date('2016-11-03'), 5],
                                    [new Date('2016-11-07'), 9], [new Date('2016-11-08'), 20], [new Date('2016-11-10'), 18]
                                ]);

                                var options = {
                                    chartArea: {
                                        'width': '90%',
                                        'height': '80%'
                                    },
                                    legend: 'none',
                                    hAxis: {
                                        textPosition: 'none'
                                    },
                                    colors: ['#AB0D06'],
                                    trendlines: {
                                        0: {
                                            type: 'exponential',
                                            color: '#333',
                                            opacity: 1
                                        },
                                        1: {
                                            type: 'linear',
                                            color: '#111',
                                            opacity: .3
                                        }
                                    }
                                };

                                var chart = new google.visualization.LineChart(document.getElementById('material-graph-animal-weight-{{ $animal->id }}'));
                                chart.draw(data, options);
                            }
                        });
                    </script>
                </div>
                -->
            </div>
        </div>
    </div>

    @if (!is_null($animal->terrarium))
    <div id="tab_environment" class="col s12">
        <div class="container">
            <div class="row">
                <div class="col s12 m5 l4">
                    <terraria-widget terrarium-id="{{ $animal->terrarium->id }}"
                                     :subscribe-add="false" :subscribe-delete="false"
                                     container-classes="row" wrapper-classes="col s12"></terraria-widget>
                </div>

                <div class="col s12 m7 l8">
                    <div class="card">
                        <div class="card-content teal lighten-1 white-text">
                            @lang('labels.sensorreadings_history')
                        </div>
                        <div class="card-content">
                            <div id="sensorgraph-terrarium-waiting-{{ $animal->terrarium_id }}" class="center">
                                <div class="btn btn-success btn-lg" id="sensorgraph-terrarium-btn_load-{{ $animal->terrarium_id }}">@lang('buttons.loadgraph')</div>
                            </div>
                            <div id="sensorgraph-terrarium-loading-{{ $animal->terrarium_id }}" class="center" style="display:none;">
                                <div class="preloader-wrapper small active">
                                    <div class="spinner-layer spinner-green-only">
                                        <div class="circle-clipper left">
                                            <div class="circle"></div>
                                        </div><div class="gap-patch">
                                            <div class="circle"></div>
                                        </div><div class="circle-clipper right">
                                            <div class="circle"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="sensorgraph-terrarium-{{ $animal->terrarium_id }}" style="width: 100%;"></div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            $('#sensorgraph-terrarium-btn_load-{{ $animal->terrarium_id }}').click(function() {
                                $('#sensorgraph-terrarium-waiting-{{ $animal->terrarium_id }}').hide();
                                $('#sensorgraph-terrarium-loading-{{ $animal->terrarium_id }}').show();
                                $.ajax({
                                    url: '{{ url('api/v1/terraria/' . $animal->terrarium_id . '/sensorreadings?history_minutes=20160') }}',
                                    type: 'GET',
                                    error: function() {
                                        notification('danger', '@lang('errors.retrievegraphdata')');
                                        $('#sensorgraph-terrarium-waiting-{{ $animal->terrarium_id }}').show();
                                        $('#sensorgraph-terrarium-loading-{{ $animal->terrarium_id }}').hide();
                                    },
                                    success: function(data) {
                                        var g = new Dygraph(
                                            document.getElementById("sensorgraph-terrarium-{{ $animal->terrarium_id }}"),
                                            data.data.csv,
                                            {
                                                'connectSeparatedPoints': true,
                                                colors: ['#5555EE', '#CC5555'],
                                                axisLineColor: '#D4D4D4'
                                            }
                                        );
                                        g.ready(function() {
                                            $('#sensorgraph-terrarium-loading-{{ $animal->terrarium_id }}').hide();
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script>
        ($(function() {
            $(document).ready(function(){
                $('ul.tabs').tabs();
            });
        }));
    </script>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating orange" href="/animals/{{ $animal->id }}/edit"><i class="material-icons">edit</i></a></li>
            <li><a class="btn-floating red" href="/animals/{{ $animal->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green" href="/animals/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop