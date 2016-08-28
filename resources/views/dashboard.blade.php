@extends('master')

@section('content')
    @include('terraria.dashboard_vue')
    @include('critical_states.dashboard_vue')

    <div id="dashboard">
        <criticalstates-widget></criticalstates-widget>
        <terraria-widget></terraria-widget>
    </div>


<!--
    <div class="row">
        @include('critical_states.hud_slice')
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="material-icons">video_label</i> @choice('components.terraria', 1) @lang('labels.critical')</h2>

                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    @include('terraria.dashboard_slice', ['terraria' => $terraria])
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="material-icons">done_all</i> @choice('components.action_sequence_schedules', 2)</h2>

                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <h4>@lang('labels.running')</h4>
                        @foreach ($ass_running as $ass)
                            <div class="col-xs-12">
                                @if($ass->running())
                                    <span class="material-icons">cached</span>
                                @elseif($ass->will_run_today())
                                    <span class="material-icons">hourglass_empty</span>
                                @elseif($ass->ran_today())
                                    <span class="material-icons">check</span>
                                @else
                                    <span class="material-icons">help_outline</span>
                                @endif
                                @lang('labels.runs_since') {{ $ass->last_start_at->format('H:i:s') }} - <a href="{{ $ass->sequence->terrarium->url() }}">{{ $ass->sequence->name }}</a>
                            </div>
                        @endforeach
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <h4>@lang('labels.queued')</h4>
                        @foreach ($ass_will_run_today as $ass)
                            <div class="col-xs-12">
                                @if($ass->running())
                                    <span class="material-icons">cached</span>
                                @elseif($ass->will_run_today())
                                    <span class="material-icons">hourglass_empty</span>
                                @elseif($ass->ran_today())
                                    <span class="material-icons">check</span>
                                @else
                                    <span class="material-icons">help_outline</span>
                                @endif
                                @lang('labels.starts_at') {{ $ass->starts_at }} - <a href="{{ $ass->sequence->terrarium->url() }}">{{ $ass->sequence->name }}</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
-->
    <script>
        $(function() {
            runPage();

            if ($(window).width() < 992) { //bootstrap md
                setTimeout(function() {
                    $('.x_content').fadeOut(1000);
                }, 100);
            }
        });
    </script>
@stop