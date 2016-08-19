@extends('master')

@section('content')


    <div class="row">
        @include('critical_states.hud_slice')
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="material-icons">columns</i> @choice('components.terraria', 1) @lang('labels.critical')</h2>

                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    @include('terraria.dashboard_slice', ['terraria' => $terraria])
                </div>
            </div>
        </div>
    </div>

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