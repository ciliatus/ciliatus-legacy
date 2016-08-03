@foreach ($terraria as $t)
<div class="col-xl-9 col-lg-6 col-md-12 col-sm-12 col-xs-12 dashboard-box">
    <div class="x_panel">

        <div class="x_title">
            <h2><a href="{{ url('terraria/' . $t->id) }}">{{ $t->friendly_name }} @lang('labels.details')</a></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="{{ url('terraria/' . $t->id . '/edit') }}">@lang('buttons.edit')</a>
                        </li>
                        <li>
                            <a href="{{ url('terraria/' . $t->id . '/delete') }}">@lang('buttons.delete')</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <div class="row">
                <div class="col-xs-12">
                    <div id="sensorgraph-terrarium-waiting-{{ $t->id }}" class="text-center" style="position: relative; top: 10px;">
                        <div class="btn btn-success btn-lg" id="sensorgraph-terrarium-btn_load-{{ $t->id }}">@lang('buttons.loadgraph')</div>
                    </div>
                    <div id="sensorgraph-terrarium-loading-{{ $t->id }}" class="text-center" style="position: relative; top: 10px; display:none;">
                        <span class="fa fa-refresh fa-spin"></span>
                        <br /><br />
                        <span class="text-muted">@lang('tooltips.loadandrendergraph')</span>
                    </div>
                    <div id="sensorgraph-terrarium-{{ $t->id }}" style="width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#sensorgraph-terrarium-btn_load-{{ $t->id }}').click(function() {
        $.ajax({
            url: '{{ url('api/v1/terraria/' . $t->id . '/sensorreadings?history_minutes=10080') }}',
            type: 'GET',
            error: function() {
                notification('danger', '@lang('errors.retrievegraphdata')');
            },
            success: function(data) {
                $('#sensorgraph-terrarium-waiting-{{ $t->id }}').hide();
                $('#sensorgraph-terrarium-loading-{{ $t->id }}').show();
                g = new Dygraph(
                        document.getElementById("sensorgraph-terrarium-{{ $t->id }}"),
                        data.data.csv,
                        {
                            'connectSeparatedPoints': true
                        }
                );
                g.ready(function() {
                    $('#sensorgraph-terrarium-loading-{{ $t->id }}').hide();
                });
            }
        });
    });
</script>
@endforeach