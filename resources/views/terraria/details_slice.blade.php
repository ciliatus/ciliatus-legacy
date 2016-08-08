<div class="col-xl-9 col-lg-6 col-md-12 col-sm-12 col-xs-12 dashboard-box">
    <div class="x_panel">

        <div class="x_title">
            <h2><a href="{{ url('terraria/' . $terrarium->id) }}">{{ $terrarium->display_name }} @lang('labels.details')</a></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ url('terraria/' . $terrarium->id . '/edit') }}">@lang('buttons.edit')</a>
                        </li>
                        <li>
                            <a href="{{ url('terraria/' . $terrarium->id . '/delete') }}">@lang('buttons.delete')</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <div class="row">
                <div class="col-xs-12">
                    <div id="sensorgraph-terrarium-waiting-{{ $terrarium->id }}" class="text-center" style="position: relative; top: 10px;">
                        <div class="btn btn-success btn-lg" id="sensorgraph-terrarium-btn_load-{{ $terrarium->id }}">@lang('buttons.loadgraph')</div>
                    </div>
                    <div id="sensorgraph-terrarium-loading-{{ $terrarium->id }}" class="text-center" style="position: relative; top: 10px; display:none;">
                        <span class="fa fa-refresh fa-spin"></span>
                        <br /><br />
                        <span class="text-muted">@lang('tooltips.loadandrendergraph')</span>
                    </div>
                    <div id="sensorgraph-terrarium-{{ $terrarium->id }}" style="width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="x_panel">

        <div class="x_title">
            <h2><a href="{{ url('terraria/' . $terrarium->id) }}">{{ $terrarium->display_name }} @choice('components.logical_sensors', 2)</a></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ url('terraria/' . $terrarium->id . '/edit') }}">@lang('buttons.edit')</a>
                        </li>
                        <li>
                            <a href="{{ url('terraria/' . $terrarium->id . '/delete') }}">@lang('buttons.delete')</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <div class="row">
                <table id="logical_sensors-datatable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th width="20"></th>
                        <th>@lang('labels.name')</th>
                        <th>@choice('components.physical_sensors', 1)</th>
                        <th>@lang('labels.current_value')</th>
                        <th>@choice('components.logical_sensor_thresholds', 1)</th>
                        <th>@lang('labels.state')</th>
                        <th>@lang('labels.actions')</th>
                    </tr>
                    </thead>


                    <tbody>
                    @foreach ($terrarium->logical_sensors as $ls)
                        <tr>
                            <td><i class="fa fa-{{ $ls->icon() }}"></i></td>
                            <td><a href="{{ $ls->url() }}">{{ $ls->name }}</a></td>
                            <td><a href="{{ $ls->physical_sensor->url() }}"><i class="fa fa-{{ $ls->physical_sensor->icon() }}"></i> {{ $ls->physical_sensor->name }}</a></td>
                            <td>{{ $ls->getCurrentCookedValue() }}</td>
                            <td>
                                @if(!is_null($ls->current_threshold()))
                                    @if(is_null($ls->current_threshold()->rawvalue_lowerlimit) && !is_null($ls->current_threshold()->rawvalue_upperlimit))
                                        max {{ $ls->current_threshold()->rawvalue_upperlimit }}
                                    @elseif(!is_null($ls->current_threshold()->rawvalue_lowerlimit) && is_null($ls->current_threshold()->rawvalue_upperlimit))
                                        min {{ $ls->current_threshold()->rawvalue_lowerlimit }}
                                    @elseif(is_null($ls->current_threshold()->rawvalue_lowerlimit) && is_null($ls->current_threshold()->rawvalue_upperlimit))
                                    @else
                                        {{ $ls->current_threshold()->rawvalue_lowerlimit }} - {{ $ls->current_threshold()->rawvalue_upperlimit }}
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if ($ls->stateOk())<i class="fa fa-check"></i>@else<i class="fa fa-times"></i>@endif
                            </td>
                            <td>
                                <a href="{{ url('logical_sensors/' . $ls->id . '/edit') }}"><i class="fa fa-edit"></i></a>
                                <a href="{{ url('logical_sensors/' . $ls->id . '/delete') }}"><i class="fa fa-times"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <script>
                    $(function() {
                        $('#logical_sensors-datatable').dataTable();
                    });
                </script>
            </div>
        </div>
    </div>

    @include('files.dashboard_slice', ['files' => $terrarium->files])
</div>
<script>
    $('#sensorgraph-terrarium-btn_load-{{ $terrarium->id }}').click(function() {
        $.ajax({
            url: '{{ url('api/v1/terraria/' . $terrarium->id . '/sensorreadings?history_minutes=10080') }}',
            type: 'GET',
            error: function() {
                notification('danger', '@lang('errors.retrievegraphdata')');
            },
            success: function(data) {
                $('#sensorgraph-terrarium-waiting-{{ $terrarium->id }}').hide();
                $('#sensorgraph-terrarium-loading-{{ $terrarium->id }}').show();
                g = new Dygraph(
                        document.getElementById("sensorgraph-terrarium-{{ $terrarium->id }}"),
                        data.data.csv,
                        {
                            'connectSeparatedPoints': true
                        }
                );
                g.ready(function() {
                    $('#sensorgraph-terrarium-loading-{{ $terrarium->id }}').hide();
                });
            }
        });
    });
</script>