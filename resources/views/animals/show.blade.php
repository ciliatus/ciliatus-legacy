@extends('master')

@section('breadcrumbs')
    <a href="/animals" class="breadcrumb">@choice('components.animals', 2)</a>
    <a href="/animals/{{ $animal->id }}" class="breadcrumb">{{ $animal->display_name }}</a>
@stop

@section('content')
    <!-- left col -->
    <div class="col s12 m5 l4 no-padding">
        <div class="col s12 m12 l12">
            <animals-widget animal-id="{{ $animal->id }}"></animals-widget>
        </div>

        @if (!is_null($animal->terrarium))
            <div class="col s12 m12 l12">
                <terraria-widget terrarium-id="{{ $animal->terrarium->id }}" :subscribe-add="false" :subscribe-delete="false"></terraria-widget>
            </div>
        @endif
    </div>

    <!-- right col -->


    <div class="col s12 m7 l4 no-padding">
        <div class="col s12">
            <animal_feeding_schedules-widget animal-id="{{ $animal->id }}"></animal_feeding_schedules-widget>
        </div>

        <div class="col s12">
            <animal_feedings-widget animal-id="{{ $animal->id }}"></animal_feedings-widget>
        </div>
    </div>

    <div class="col s12 m12 l4 no-padding">
        <div class="col s12">
            <files-widget source-filter="?filter[belongsTo_type]=Animal&filter[belongsTo_id]={{ $animal->id }}"
                          belongs-to_type="Animal" belongs-to_id="{{ $animal->id }}"></files-widget>
        </div>
    </div>


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