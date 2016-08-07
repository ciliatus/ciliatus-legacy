    <div class="x_panel">

        <div class="x_title">
            <h2>Files</h2>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <table id="file-datatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="20"></th>
                        <th>@lang('labels.display_name')</th>
                        <th>@lang('labels.size')</th>
                        <th>@lang('labels.belongsto')</th>
                        <th>@lang('labels.created_at')</th>
                    </tr>
                </thead>


                <tbody>
                    @foreach ($files as $f)
                    <tr>
                        <td><i class="fa fa-{{ $f->icon() }}"></i></td>
                        <td><a href="{{ url('files/' . $f->id) }}">{{ $f->display_name }}</a></td>
                        <td>{{ $f->sizeReadable() }}</td>
                        <td>
                            @if(!is_null($f->belongs_to()))
                            <a href="{{ $f->belongs_to()->url() }}"><i class="fa fa-{{ $f->belongs_to()->icon() }}"></i> {{ $f->belongs_to()->display_name}}</a>
                            @endif
                        </td>
                        <td>{{ $f->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <script>
                $(function() {
                    $('#file-datatable').dataTable();
                });
            </script>
        </div>
    </div>