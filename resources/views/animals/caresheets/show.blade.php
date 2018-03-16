@extends('master_printable')

@section('created')
    {!! $caresheet->created_at->toDateTimeString() !!}
@stop

@section('content')
    <page size="A4">
        <div class="row">
            <div class="col s9">Ciliatus - www.ciliatus.io</div>
            <div class="col s3 right-align">@yield('created')</div>
        </div>

        <div class="row center-align">
            <h4><i class="mdi mdi-18px mdi-paw"></i> @choice('labels.caresheets', 1) {{ $caresheet->belongsTo_object()->display_name }}</h4>
        </div>

        <div class="row">
            <div class="col s3">
                <p>
                    @lang('labels.latin_name')<br />
                    @lang('labels.gender')<br />
                    @lang('labels.date_birth')
                </p>
                <p>
                    @lang('labels.weight')<br />
                    @lang('labels.last_feeding')
                </p>
            </div>
            <div class="col s9">
                <p>
                    <strong>{{ $caresheet->belongsTo_object()->lat_name }}</strong> <i>{{ $caresheet->belongsTo_object()->common_name }}</i><br />
                    @if (!is_null($caresheet->belongsTo_object()->gender))<strong>@lang('labels.gender_' . $caresheet->belongsTo_object()->gender)</strong>@endif<br />
                    @if (!is_null($caresheet->belongsTo_object()->birth_date))<strong>{{ $caresheet->belongsTo_object()->birth_date->toDateString() }}</strong>@endif
                </p>
                <p>
                    @if (!is_null($caresheet->belongsTo_object()->last_weighing()))<strong>{{ $caresheet->belongsTo_object()->last_weighing()->value }}{{ $caresheet->belongsTo_object()->last_weighing()->name }}</strong> <i>{{ $caresheet->belongsTo_object()->last_weighing()->created_at->toDateString() }}</i><br />@endif
                    @if (!is_null($caresheet->belongsTo_object()->last_feeding()))<strong>{{ $caresheet->belongsTo_object()->last_feeding()->name }}</strong> <i>{{ $caresheet->belongsTo_object()->last_feeding()->created_at->toDateString() }}</i>@endif
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col s12">
                <h5><i class="mdi mdi-18px mdi-trackpad"></i> @choice('labels.terraria', 1) - <i>{{ $caresheet->property('AnimalCaresheetProperty', 'sensor_history_days', true) }} @choice('units.days', $caresheet->property('AnimalCaresheetProperty', 'sensor_history_days', true))</i></h5>

                <table>
                    <thead>
                        <th> </th>
                        <th>@lang('labels.average')</th>
                        <th>@lang('labels.min')</th>
                        <th>@lang('labels.max')</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>@lang('labels.during_day')</strong></td>
                            <td>{{ round($caresheet->property('AnimalCaresheetProperty', 'terrarium_average_temperature_day', true), 1) }}°C / {{ round($caresheet->property('AnimalCaresheetProperty', 'terrarium_average_humidity_day', true), 1) }}%</td>
                            <td>{{ round($caresheet->property('AnimalCaresheetProperty', 'terrarium_min_temperature_day', true), 1) }}°C / {{ round($caresheet->property('AnimalCaresheetProperty', 'terrarium_min_humidity_day', true), 1) }}%</td>
                            <td>{{ round($caresheet->property('AnimalCaresheetProperty', 'terrarium_max_temperature_day', true), 1) }}°C / {{ round($caresheet->property('AnimalCaresheetProperty', 'terrarium_max_humidity_day', true), 1) }}%</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('labels.during_night')</strong></td>
                            <td>{{ round($caresheet->property('AnimalCaresheetProperty', 'terrarium_average_temperature_night', true), 1) }}°C / {{ round($caresheet->property('AnimalCaresheetProperty', 'terrarium_average_humidity_night', true), 1) }}%</td>
                            <td>{{ round($caresheet->property('AnimalCaresheetProperty', 'terrarium_min_temperature_night', true), 1) }}°C / {{ round($caresheet->property('AnimalCaresheetProperty', 'terrarium_min_humidity_night', true), 1) }}%</td>
                            <td>{{ round($caresheet->property('AnimalCaresheetProperty', 'terrarium_max_temperature_night', true), 1) }}°C / {{ round($caresheet->property('AnimalCaresheetProperty', 'terrarium_max_humidity_night', true), 1) }}%</td>
                        </tr>
                        <tr>
                            <td><strong>@lang('labels.total')</strong></td>
                            <td>{{ round($caresheet->property('AnimalCaresheetProperty', 'terrarium_average_temperature', true), 1) }}°C / {{ round($caresheet->property('AnimalCaresheetProperty', 'terrarium_average_humidity', true), 1) }}%</td>
                            <td>{{ round($caresheet->property('AnimalCaresheetProperty', 'terrarium_min_temperature', true), 1) }}°C / {{ round($caresheet->property('AnimalCaresheetProperty', 'terrarium_min_humidity', true), 1) }}%</td>
                            <td>{{ round($caresheet->property('AnimalCaresheetProperty', 'terrarium_max_temperature', true), 1) }}°C / {{ round($caresheet->property('AnimalCaresheetProperty', 'terrarium_max_humidity', true), 1) }}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col s6">
                <h5>
                    <i class="mdi mdi-18px mdi-silverware-variant"></i>
                    @lang('labels.feedings')
                </h5>
                <br />

                @if ($feedings->count() < 1)
                    @lang('tooltips.no_data')
                @else
                    @foreach($feedings as $feeding)
                        {{ $feeding->created_at->toDateString() }}: <strong>{{ $feeding->name }}</strong><br />
                    @endforeach
                @endif
            </div>
            <div class="col s6">

                <h5><i class="mdi mdi-18px mdi-format-vertical-align-bottom"></i> @lang('labels.weighprogression')</h5>
                <br />

                @if ($weighings->count() < 1)
                    @lang('tooltips.no_data')
                @else
                    @foreach($weighings as $weighing)
                        {{ $weighing->created_at->toDateString() }}: <strong>{{ $weighing->value }}{{ $weighing->name }}</strong><br />
                    @endforeach
                @endif

                <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                @if ($weighings->count() > 0)
                <script type="text/javascript">
                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                            ['@lang('labels.date')', '@lang('labels.weight')/g'],
                            @foreach ($weighings as $weighing)
                            [new Date('{{ $weighing->created_at->toDateString() }}'), {{ $weighing->value }}],
                            @endforeach
                        ]);

                        var options = {
                            curveType: 'function',
                            legend: { position: 'bottom' },
                            pointSize: 4,
                            hAxis: {
                                ticks: [new Date('{{ $weighings->last()->created_at->toDateString() }}'),
                                        new Date('{{ $weighings->first()->created_at->toDateString() }}')]
                            }
                        };

                        var chart = new google.visualization.LineChart(document.getElementById('weight_chart'));

                        chart.draw(data, options);
                    }
                </script>
                @endif
                <div id="weight_chart" style="width: calc(100px + 100%); height: 300px; position: relative; left: -90px;"></div>
            </div>
        </div>
    </page>

    <page size="A4">
        <div class="row">
            <div class="col s6">Ciliatus - www.ciliatus.io</div>
            <div class="col s6 right-align">@yield('created')</div>
        </div>

        <div class="row center-align">
            <h4><i class="mdi mdi-18px mdi-paw"></i> @choice('labels.caresheets', 1) {{ $caresheet->belongsTo_object()->display_name }}</h4>
        </div>

        <div class="row">
            <div class="col s12">
                <h5>
                    <i class="mdi mdi-18px mdi-format-list-bulleted"></i>
                    @choice('labels.biography_entries', 2) -
                    <i>{{ $caresheet->property('AnimalCaresheetProperty', 'data_history_days', true) }} @choice('units.days', $caresheet->property('AnimalCaresheetProperty', 'data_history_days', true))</i>
                </h5>
                <br />

                @if ($biography_entries->count() < 1)
                    @lang('tooltips.no_data')
                @else
                    @foreach($biography_entries as $entry)
                        <strong>{{ $entry->name }}</strong> <i>{{ $entry->created_at->toDateString() }} - {{ $entry->propertyOfType('BiographyEntryCategory')->name }}</i>
                        <p>
                            {!! $entry->value !!}
                        </p>
                        <hr />
                    @endforeach
                @endif
            </div>
        </div>
    </page>
@stop