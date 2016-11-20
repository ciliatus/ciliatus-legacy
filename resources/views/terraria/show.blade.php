@extends('master')

@section('content')
    <!-- left col -->
    <div class="col s12 m12 l4 no-padding">
        <div class="col s12 m6 l12">
            <terraria-widget terrarium-id="{{ $terrarium->id }}" :subscribe-add="false" :subscribe-delete="false"></terraria-widget>
        </div>

        @if (!is_null($terrarium->animals))
            @foreach ($terrarium->animals as $animal)
            <div class="col s12 m6 l12">
                <animals-widget animal-id="{{ $animal->id }}"></animals-widget>
            </div>
            @endforeach
        @endif
    </div>

    <!-- right col -->
    <div class="col s12 m12 l8 no-padding">
        <div class="card">
            <div class="card-content light-blue darken-1 white-text">
                count @lang('labels.measurement_count')
            </div>

            <div class="card-content">
                <span class="card-title activator truncate">
                    @lang('labels.sensorreadings_history')
                    <i class="material-icons right">more_vert</i>
                </span>
                <p>
                <div id="sensorgraph-terrarium-waiting-{{ $terrarium->id }}" class="text-center" style="position: relative; top: 10px;">
                    <div class="btn btn-success btn-lg" id="sensorgraph-terrarium-btn_load-{{ $terrarium->id }}">@lang('buttons.loadgraph')</div>
                </div>
                <div id="sensorgraph-terrarium-loading-{{ $terrarium->id }}" class="text-center" style="position: relative; top: 10px; display:none;">
                    <div class="progress">
                        <div class="indeterminate"></div>
                    </div>
                </div>
                <div id="sensorgraph-terrarium-{{ $terrarium->id }}" style="width: 100%;"></div>
                </p>
            </div>

            <div class="card-reveal">
                <span class="card-title grey-text text-darken-4"><i class="material-icons right">close</i></span>
                <p>

                </p>
            </div>
        </div>

    </div>

    <div class="col s12 m12 l6 no-padding">
        <files-widget source-filter="?filter[belongsTo_type]=Terrarium&filter[belongsTo_id]={{ $terrarium->id }}"
                      belongs-to_type="Terrarium" belongs-to_id="{{ $terrarium->id }}"></files-widget>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#sensorgraph-terrarium-btn_load-{{ $terrarium->id }}').click(function() {
                $('#sensorgraph-terrarium-waiting-{{ $terrarium->id }}').hide();
                $('#sensorgraph-terrarium-loading-{{ $terrarium->id }}').show();
                $.ajax({
                    url: '{{ url('api/v1/terraria/' . $terrarium->id . '/sensorreadings?history_minutes=20160') }}',
                    type: 'GET',
                    error: function() {
                        notification('danger', '@lang('errors.retrievegraphdata')');
                        $('#sensorgraph-terrarium-waiting-{{ $terrarium->id }}').show();
                        $('#sensorgraph-terrarium-loading-{{ $terrarium->id }}').hide();
                    },
                    success: function(data) {
                        g = new Dygraph(
                                document.getElementById("sensorgraph-terrarium-{{ $terrarium->id }}"),
                                data.data.csv,
                                {
                                    'connectSeparatedPoints': true,
                                    colors: ['#5555EE', '#CC5555'],
                                    axisLineColor: '#D4D4D4'
                                }
                        );
                        g.ready(function() {
                            $('#sensorgraph-terrarium-loading-{{ $terrarium->id }}').hide();
                        });
                    }
                });
            });
        });
    </script>
@stop