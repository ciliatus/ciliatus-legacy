@extends('master')

@section('content')
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Telegram Setup</h2>

            <div class="clearfix"></div>
        </div>
        <div class="x_content">


            <!-- Smart Wizard -->
            <p>@lang('messages.users.setup_telegram_description')</p>
            <div id="wizard" class="form_wizard wizard_horizontal">
                <ul class="wizard_steps">
                    <li>
                        <a href="#step-1">
                            <span class="step_no">1</span>
                            <span class="step_descr">
                                @lang('labels.step') 1<br />
                                <small>@lang('tooltips.phone_number')</small>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="#step-2">
                            <span class="step_no">2</span>
                            <span class="step_descr">
                                @lang('labels.step') 2<br />
                                <small>@lang('tooltips.contact_bot')</small>
                            </span>
                        </a>
                    </li>
                    <li>
                        <a href="#step-3">
                            <span class="step_no">3</span>
                            <span class="step_descr">
                                @lang('labels.step') 3<br />
                                <small>@lang('tooltips.wait_confirmation')</small>
                            </span>
                        </a>
                    </li>
                </ul>
                <div id="step-1" style="padding-top: 40px; height: 200px;">
                    <form class="form-horizontal form-label-left" name="f_edit_user_telegram-step1" id="f_edit_user_telegram-step1"
                          data-callback="wizard_validate_step" data-callback-param="1"
                          action="{{ url('api/v1/user_settings/' . Auth::user()->id) }}" data-method="PUT">

                        <input type="hidden" name="name" value="notifications_telegram_phone_no">
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">@lang('labels.phone_number')
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="value" value="{{ Auth::user()->setting('notifications_telegram_phone_no') }}" required="required" class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                    </form>

                </div>
                <div id="step-2" style="padding-top: 40px; height: 200px;">
                    <div class="text-center" style="padding: 30px;" data-livedata="true" data-livedatainterval="5" data-livedatasource="{{ url('api/v1/users/' . Auth::user()->id . '/setting/notifications_telegram_chat_id') }}" data-livedatacallback="wizard_wait_for_telegram_contact">
                        <h2><i class="fa fa-spin fa-refresh"></i><br /></h2>
                        <h4>@lang('messages.user.setup_telegram_waiting_for_contact')</h4>
                    </div>
                </div>
                <div id="step-3" style="padding-top: 40px; height: 200px;">
                    <div class="text-center" style="padding: 30px;">
                        <h2><i class="fa fa-check"></i><br /></h2>
                        <h4>@lang('messages.user.setup_telegram_waiting_for_contact')</h4>
                    </div>
                </div>

            </div>
            <!-- End SmartWizard Content -->
        </div>
    </div>
</div>
<!-- FastClick -->
{!! Html::script('vendors/fastclick/lib/fastclick.js') !!}
<!-- NProgress -->
{!! Html::script('vendors/nprogress/nprogress.js') !!}
<!-- jQuery Smart Wizard -->
{!! Html::script('vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js') !!}
<script>
    var waitingForResponse = false;

    function doneStep(stepnumber)
    {
        console.log('doneStep ' + stepnumber);
        $('#wizard').smartWizard('goForward');
        waitingForResponse = false;
    }
    function errorStep(stepnumber)
    {
        waitingForResponse = false;
    }

    function validateStep(stepnumber)
    {
        console.log('validateStep' + stepnumber);

        if (stepnumber == 1) {
            waitingForResponse = true;
            console.log($('#f_edit_user_telegram-step1').submit());
        }
        if (stepnumber == 2) {
            return true;
        }
    }

    function leaveAStepCallback(obj, context)
    {
        if (waitingForResponse === false)
            return validateStep(context.fromStep);

        return true;
    }

    $(function()
    {
        $('#wizard').smartWizard({
            onLeaveStep: leaveAStepCallback,
            onFinish: function() { window.location.replace('{{ url('users/' . Auth::user()->id . '/edit') }}')}
        });

        $('.buttonNext').addClass('btn btn-success');
        $('.buttonPrevious').remove(); //addClass('btn btn-primary');
        $('.buttonFinish').addClass('btn btn-default');

        runPage();
    })
</script>
@stop