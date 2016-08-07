<div class="col-md-12 col-sm-12 col-lg-12 col-xl-12 col-xs-12 dashboard-box" id="log-index">
    <div class="x_panel">

        <div class="x_title">
            <h2>@lang('labels.log')</h2>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <table id="log-datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="20"></th>
                        <th>@lang('labels.source')</th>
                        <th>@lang('labels.action')</th>
                        <th>@lang('labels.target')</th>
                        <th>@lang('labels.associated_with')</th>
                        <th>@lang('labels.created_at')</th>
                    </tr>
                </thead>


                <tbody>
                    @foreach ($logs as $l)
                    <tr>
                        <td><i class="fa fa-{{ $l->icon() }}"></i></td>
                        <td>
                            @if(!is_null($l->source()))
                                <a href="{{ $l->source()->url() }}"><i class="fa fa-{{ $l->source()->icon() }}"></i> {{ $l->source()->name}} {{ $l->source()->display_name}}</a>
                            @endif
                        </td>
                        <td>{{ $l->action }}</td>
                        <td>
                            @if(!is_null($l->target()))
                            <a href="{{ $l->target()->url() }}"><i class="fa fa-{{ $l->target()->icon() }}"></i> {{ $l->target()->name}} {{ $l->source()->display_name}}</a>
                            @endif
                        </td>
                        <td>
                            @if(!is_null($l->associatedWith()))
                                <a href="{{ $l->associatedWith()->url() }}"><i class="fa fa-{{ $l->associatedWith()->icon() }}"></i> {{ $l->associatedWith()->name}} {{ $l->source()->display_name}}</a>
                            @endif
                        </td>
                        <td>{{ $l->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <script>
                $(function() {
                    $('#log-datatable').dataTable();
                });
            </script>
        </div>
    </div>
</div>