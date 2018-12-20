@extends('master')


@section('content')
    <div class="">
        <div class="row">
            <div class="input-field col s12 m5 l4">
                <form action="/system_logs" method="GET">
                    <select name="l" id="select-logfile" onchange="this.form.submit()">
                        <option value="" disabled selected>@lang('labels.choose_logfile')</option>
                        @foreach($files as $file)
                            <option value="{{ base64_encode($file) }}" @if($current_file == $file) selected @endif>{{$file}}</option>
                        @endforeach
                    </select>
                    <label>@lang('labels.choose_logfile')</label>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                @if ($logs === null)
                    <div>
                    Log file >50M, please download it.
                    </div>
                @else
                <table id="table-log" class="responsive highlight collapsible" data-collapsible="expandable">
                    <thead>
                    <tr>
                        <th style="min-width: 80px;">Level</th>
                        <th style="min-width: 120px;">Context</th>
                        <th style="min-width: 180px;">Date</th>
                        <th>Content</th>
                    </tr>
                    </thead>

                    @foreach($logs as $key => $log)
                    <tbody>
                    <tr class="collapsible-tr-header" onclick="window.collapseTr($(this))">
                        <td>
                            <span class="center">
                                <i class="mdi mdi-24px {{ $log['level_color'] }}-text mdi-{{ $log['level_icon'] }}"></i>
                                {{ $log['level'] }}
                            </span>
                        </td>
                        <td class="text">{{ $log['context'] }}</td>
                        <td class="date">{{ $log['date'] }}</td>
                        <td class="text">
                            @if ($log['stack'])
                                <i class="mdi mdi-24px mdi-dots-vertical right"></i>
                            @endif

                            {{ $log['text'] }}

                            @if (isset($log['in_file']))
                                <br />
                                {{ $log['in_file'] }}
                            @endif
                        </td>
                    </tr>

                    @if ($log['stack'])
                    <tr class="collapsible-tr-body">
                        <td colspan="4">
                            <pre>{{ trim($log['stack']) }}</pre>
                        </td>
                    </tr>
                    @endif
                    </tbody>
                    @endforeach

                </table>
                @endif
                <div>
                    @if($current_file)
                          <a href="?dl={{ base64_encode($current_file) }}" class="btn teal">
                              <i class="mdi mdi-18px mdi-download"></i> @lang('buttons.download')</a>
                          <a id="delete-log" href="?del={{ base64_encode($current_file) }}" class="btn red">
                              <i class="mdi mdi-18px mdi-delete"></i> @lang('buttons.delete_type', ['type' => trans('labels.file')])</a>
                          @if(count($files) > 1)
                            <a id="delete-all-log" href="?delall=true" class="btn red">
                                <i class="mdi mdi-18px mdi-delete-sweep"></i> @lang('buttons.delete_all_type', ['type' => trans('labels.files')])</a>
                          @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop
