@extends('master')


@section('content')
    <div class="container">
        <div class="row">

            <div class="col s12 m6 l6">
                <div class="card">
                    <div class="card-header">
                        @lang('labels.software_version')
                    </div>

                    <div class="card-content">
                        @if ($version == false)
                        <h5><i class="material-icons red-text">close</i> Ciliatus</h5>
                        <p>
                            <strong>{{ config('app.version') }}:</strong> <i class="material-icons green-text">close</i>
                        </p>
                        @elseif ($version['current'] == true)
                        <h5><i class="material-icons green-text">check</i> Ciliatus</h5>
                        <p>
                            <strong>{{ config('app.version') }}:</strong> <i class="material-icons green-text">check</i>
                            <span>@lang('tooltips.ciliatus_up_to_date')</span>
                        </p>
                        @else
                        <h5><i class="material-icons orange-text">close</i> Ciliatus</h5>
                        <p>
                            <strong>{{ config('app.version') }}:</strong> <i class="material-icons orange-text">close</i>
                            <span>@lang('tooltips.ciliatus_not_up_to_date', [
                                'url' => 'https://github.com/matthenning/ciliatus/releases'
                            ])</span>
                        </p>
                        @endif
                    </div>

                </div>
            </div>

            <div class="col s12 m6 l6">
                <div class="card">

                    <div class="card-header">
                        @lang('labels.features')
                    </div>

                    <div class="card-content">
                        @if ($influx_db_status === true)
                            <h5><i class="material-icons green-text">check</i> InfluxDB</h5>
                            <p>
                                <strong>Database connection:</strong> <i class="material-icons green-text">check</i>
                            </p>
                        @else
                            <h5><i class="material-icons red-text">close</i>InfluxDB</h5>
                            <p>
                                <strong>Database connection:</strong> <i class="material-icons red-text">close</i> {{ $influx_db_status }}
                            </p>
                        @endif
                    </div>

                    <div class="card-content">
                        <h5>
                            @if($api_ai_status['you'] && $api_ai_status['default'] && $api_ai_status['attempt'] === true)
                                <i class="material-icons green-text">check</i>
                            @elseif(($api_ai_status['you'] || $api_ai_status['default']) && $api_ai_status['attempt'] === true)
                                <i class="material-icons orange-text">check</i>
                            @else
                                <i class="material-icons red-text">close</i>
                            @endif
                            API.AI / Voice Recognition
                        </h5>
                        <p>
                            <strong>Your language:</strong>
                            @if($api_ai_status['you'])
                                <i class="material-icons green-text">check</i>
                            @else
                                <i class="material-icons red-text">close</i>
                            @endif
                            <br />
                            <strong>Default language:</strong>
                            @if($api_ai_status['default'])
                                <i class="material-icons green-text">check</i>
                            @else
                                <i class="material-icons red-text">close</i>
                            @endif
                            <br />
                            <strong>Test request:</strong>
                            @if ($api_ai_status['attempt'] === true)
                                <i class="material-icons green-text">check</i>
                            @else
                                <i class="material-icons red-text">close</i> {{ $api_ai_status['attempt'] }}
                            @endif
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
@stop
