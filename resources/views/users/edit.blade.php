@extends('master')

@section('breadcrumbs')
<a href="/users" class="breadcrumb">@choice('components.users', 2)</a>
<a href="/users/{{ $user->id }}" class="breadcrumb">{{ $user->name }}</a>
<a href="/users/{{ $user->id }}/edit" class="breadcrumb">@lang('buttons.edit')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/users/' . $user->id) }}" data-method="PUT"
                          data-redirect-success="{{ url('/') }}">
                        <div class="card-content">

                            <span class="card-title activator grey-text text-darken-4 truncate">
                                <span>{{ $user->display_name }}</span>
                            </span>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" readonly="readonly" placeholder="ID" name="id" value="{{ $user->id }}">
                                    <label for="id">ID</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.name')" name="name" value="{{ $user->name }}"
                                           @if(Gate::denies('admin')) readonly="readonly" @endif>
                                    <label for="name">@lang('labels.name')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.email')" name="email" value="{{ $user->email }}"
                                           @if(Gate::denies('admin')) readonly="readonly" @endif>
                                    <label for="name">@lang('labels.email')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12 m6">
                                    <select name="timezone">
                                        <option value="Pacific/Midway" @if($user->timezone == "Pacific/Midway") selected @endif>(UTC-11:00) Midway Island</option>
                                        <option value="Pacific/Samoa" @if($user->timezone == "Pacific/Samoa") selected @endif>(UTC-11:00) Samoa</option>
                                        <option value="Pacific/Honolulu" @if($user->timezone == "Pacific/Honolulu") selected @endif>(UTC-10:00) Hawaii</option>
                                        <option value="US/Alaska" @if($user->timezone == "US/Alaska") selected @endif>(UTC-09:00) Alaska</option>
                                        <option value="America/Los_Angeles" @if($user->timezone == "America/Los_Angeles") selected @endif>(UTC-08:00) Pacific Time (US &amp; Canada)</option>
                                        <option value="America/Tijuana" @if($user->timezone == "America/Tijuana") selected @endif>(UTC-08:00) Tijuana</option>
                                        <option value="US/Arizona" @if($user->timezone == "US/Arizona") selected @endif>(UTC-07:00) Arizona</option>
                                        <option value="America/Chihuahua" @if($user->timezone == "America/Chihuahua") selected @endif>(UTC-07:00) Chihuahua</option>
                                        <option value="America/Chihuahua" @if($user->timezone == "America/Chihuahua") selected @endif>(UTC-07:00) La Paz</option>
                                        <option value="America/Mazatlan" @if($user->timezone == "America/Mazatlan") selected @endif>(UTC-07:00) Mazatlan</option>
                                        <option value="US/Mountain" @if($user->timezone == "US/Mountain") selected @endif>(UTC-07:00) Mountain Time (US &amp; Canada)</option>
                                        <option value="America/Managua" @if($user->timezone == "America/Managua") selected @endif>(UTC-06:00) Central America</option>
                                        <option value="US/Central" @if($user->timezone == "US/Central") selected @endif>(UTC-06:00) Central Time (US &amp; Canada)</option>
                                        <option value="America/Mexico_City" @if($user->timezone == "America/Mexico_City") selected @endif>(UTC-06:00) Guadalajara</option>
                                        <option value="America/Mexico_City" @if($user->timezone == "America/Mexico_City") selected @endif>(UTC-06:00) Mexico City</option>
                                        <option value="America/Monterrey" @if($user->timezone == "America/Monterrey") selected @endif>(UTC-06:00) Monterrey</option>
                                        <option value="Canada/Saskatchewan" @if($user->timezone == "Canada/Saskatchewan") selected @endif>(UTC-06:00) Saskatchewan</option>
                                        <option value="America/Bogota" @if($user->timezone == "America/Bogota") selected @endif>(UTC-05:00) Bogota</option>
                                        <option value="US/Eastern" @if($user->timezone == "US/Eastern") selected @endif>(UTC-05:00) Eastern Time (US &amp; Canada)</option>
                                        <option value="US/East-Indiana" @if($user->timezone == "US/East-Indiana") selected @endif>(UTC-05:00) Indiana (East)</option>
                                        <option value="America/Lima" @if($user->timezone == "America/Lima") selected @endif>(UTC-05:00) Lima</option>
                                        <option value="America/Bogota" @if($user->timezone == "America/Bogota") selected @endif>(UTC-05:00) Quito</option>
                                        <option value="Canada/Atlantic" @if($user->timezone == "Canada/Atlantic") selected @endif>(UTC-04:00) Atlantic Time (Canada)</option>
                                        <option value="America/Caracas" @if($user->timezone == "America/Caracas") selected @endif>(UTC-04:30) Caracas</option>
                                        <option value="America/La_Paz" @if($user->timezone == "America/La_Paz") selected @endif>(UTC-04:00) La Paz</option>
                                        <option value="America/Santiago" @if($user->timezone == "America/Santiago") selected @endif>(UTC-04:00) Santiago</option>
                                        <option value="Canada/Newfoundland" @if($user->timezone == "Canada/Newfoundland") selected @endif>(UTC-03:30) Newfoundland</option>
                                        <option value="America/Sao_Paulo" @if($user->timezone == "America/Sao_Paulo") selected @endif>(UTC-03:00) Brasilia</option>
                                        <option value="America/Argentina/Buenos_Aires" @if($user->timezone == "America/Argentina/Buenos_Aires") selected @endif>(UTC-03:00) Buenos Aires</option>
                                        <option value="America/Argentina/Buenos_Aires" @if($user->timezone == "America/Argentina/Buenos_Aires") selected @endif>(UTC-03:00) Georgetown</option>
                                        <option value="America/Godthab" @if($user->timezone == "America/Godthab") selected @endif>(UTC-03:00) Greenland</option>
                                        <option value="America/Noronha" @if($user->timezone == "America/Noronha") selected @endif>(UTC-02:00) Mid-Atlantic</option>
                                        <option value="Atlantic/Azores" @if($user->timezone == "Atlantic/Azores") selected @endif>(UTC-01:00) Azores</option>
                                        <option value="Atlantic/Cape_Verde" @if($user->timezone == "Atlantic/Cape_Verde") selected @endif>(UTC-01:00) Cape Verde Is.</option>
                                        <option value="Africa/Casablanca" @if($user->timezone == "Africa/Casablanca") selected @endif>(UTC+00:00) Casablanca</option>
                                        <option value="Europe/London" @if($user->timezone == "Europe/London") selected @endif>(UTC+00:00) Edinburgh</option>
                                        <option value="Etc/Greenwich" @if($user->timezone == "Etc/Greenwich") selected @endif>(UTC+00:00) Greenwich Mean Time : Dublin</option>
                                        <option value="Europe/Lisbon" @if($user->timezone == "Europe/Lisbon") selected @endif>(UTC+00:00) Lisbon</option>
                                        <option value="Europe/London" @if($user->timezone == "Europe/London") selected @endif>(UTC+00:00) London</option>
                                        <option value="Africa/Monrovia" @if($user->timezone == "Africa/Monrovia") selected @endif>(UTC+00:00) Monrovia</option>
                                        <option value="UTC" @if($user->timezone == "UTC" || is_null($user->timezone)) selected @endif>(UTC+00:00) UTC</option>
                                        <option value="Europe/Amsterdam" @if($user->timezone == "Europe/Amsterdam") selected @endif>(UTC+01:00) Amsterdam</option>
                                        <option value="Europe/Belgrade" @if($user->timezone == "Europe/Belgrade") selected @endif>(UTC+01:00) Belgrade</option>
                                        <option value="Europe/Berlin" @if($user->timezone == "Europe/Berlin") selected @endif>(UTC+01:00) Berlin</option>
                                        <option value="Europe/Bern" @if($user->timezone == "Europe/Bern") selected @endif>(UTC+01:00) Bern</option>
                                        <option value="Europe/Bratislava" @if($user->timezone == "Europe/Bratislava") selected @endif>(UTC+01:00) Bratislava</option>
                                        <option value="Europe/Brussels" @if($user->timezone == "Europe/Brussels") selected @endif>(UTC+01:00) Brussels</option>
                                        <option value="Europe/Budapest" @if($user->timezone == "Europe/Budapest") selected @endif>(UTC+01:00) Budapest</option>
                                        <option value="Europe/Copenhagen" @if($user->timezone == "Europe/Copenhagen") selected @endif>(UTC+01:00) Copenhagen</option>
                                        <option value="Europe/Ljubljana" @if($user->timezone == "Europe/Ljubljana") selected @endif>(UTC+01:00) Ljubljana</option>
                                        <option value="Europe/Madrid" @if($user->timezone == "Europe/Madrid") selected @endif>(UTC+01:00) Madrid</option>
                                        <option value="Europe/Paris" @if($user->timezone == "Europe/Paris") selected @endif>(UTC+01:00) Paris</option>
                                        <option value="Europe/Prague" @if($user->timezone == "Europe/Prague") selected @endif>(UTC+01:00) Prague</option>
                                        <option value="Europe/Rome" @if($user->timezone == "Europe/Rome") selected @endif>(UTC+01:00) Rome</option>
                                        <option value="Europe/Sarajevo" @if($user->timezone == "Europe/Sarajevo") selected @endif>(UTC+01:00) Sarajevo</option>
                                        <option value="Europe/Skopje" @if($user->timezone == "Europe/Skopje") selected @endif>(UTC+01:00) Skopje</option>
                                        <option value="Europe/Stockholm" @if($user->timezone == "Europe/Stockholm") selected @endif>(UTC+01:00) Stockholm</option>
                                        <option value="Europe/Vienna" @if($user->timezone == "Europe/Vienna") selected @endif>(UTC+01:00) Vienna</option>
                                        <option value="Europe/Warsaw" @if($user->timezone == "Europe/Warsaw") selected @endif>(UTC+01:00) Warsaw</option>
                                        <option value="Africa/Lagos" @if($user->timezone == "Africa/Lagos") selected @endif>(UTC+01:00) West Central Africa</option>
                                        <option value="Europe/Zagreb" @if($user->timezone == "Europe/Zagreb") selected @endif>(UTC+01:00) Zagreb</option>
                                        <option value="Europe/Athens" @if($user->timezone == "Europe/Athens") selected @endif>(UTC+02:00) Athens</option>
                                        <option value="Europe/Bucharest" @if($user->timezone == "Europe/Bucharest") selected @endif>(UTC+02:00) Bucharest</option>
                                        <option value="Africa/Cairo" @if($user->timezone == "Africa/Cairo") selected @endif>(UTC+02:00) Cairo</option>
                                        <option value="Africa/Harare" @if($user->timezone == "Africa/Harare") selected @endif>(UTC+02:00) Harare</option>
                                        <option value="Europe/Helsinki" @if($user->timezone == "Europe/Helsinki") selected @endif>(UTC+02:00) Helsinki</option>
                                        <option value="Europe/Istanbul" @if($user->timezone == "Europe/Istanbul") selected @endif>(UTC+02:00) Istanbul</option>
                                        <option value="Asia/Jerusalem" @if($user->timezone == "Asia/Jerusalem") selected @endif>(UTC+02:00) Jerusalem</option>
                                        <option value="Europe/Helsinki" @if($user->timezone == "Europe/Helsinki") selected @endif>(UTC+02:00) Kyiv</option>
                                        <option value="Africa/Johannesburg" @if($user->timezone == "Africa/Johannesburg") selected @endif>(UTC+02:00) Pretoria</option>
                                        <option value="Europe/Riga" @if($user->timezone == "Europe/Riga") selected @endif>(UTC+02:00) Riga</option>
                                        <option value="Europe/Sofia" @if($user->timezone == "Europe/Sofia") selected @endif>(UTC+02:00) Sofia</option>
                                        <option value="Europe/Tallinn" @if($user->timezone == "Europe/Tallinn") selected @endif>(UTC+02:00) Tallinn</option>
                                        <option value="Europe/Vilnius" @if($user->timezone == "Europe/Vilnius") selected @endif>(UTC+02:00) Vilnius</option>
                                        <option value="Asia/Baghdad" @if($user->timezone == "Asia/Baghdad") selected @endif>(UTC+03:00) Baghdad</option>
                                        <option value="Asia/Kuwait" @if($user->timezone == "Asia/Kuwait") selected @endif>(UTC+03:00) Kuwait</option>
                                        <option value="Europe/Minsk" @if($user->timezone == "Europe/Minsk") selected @endif>(UTC+03:00) Minsk</option>
                                        <option value="Africa/Nairobi" @if($user->timezone == "Africa/Nairobi") selected @endif>(UTC+03:00) Nairobi</option>
                                        <option value="Asia/Riyadh" @if($user->timezone == "Asia/Riyadh") selected @endif>(UTC+03:00) Riyadh</option>
                                        <option value="Europe/Volgograd" @if($user->timezone == "Europe/Volgograd") selected @endif>(UTC+03:00) Volgograd</option>
                                        <option value="Asia/Tehran" @if($user->timezone == "Asia/Tehran") selected @endif>(UTC+03:30) Tehran</option>
                                        <option value="Asia/Muscat" @if($user->timezone == "Asia/Muscat") selected @endif>(UTC+04:00) Abu Dhabi</option>
                                        <option value="Asia/Baku" @if($user->timezone == "Asia/Baku") selected @endif>(UTC+04:00) Baku</option>
                                        <option value="Europe/Moscow" @if($user->timezone == "Europe/Moscow") selected @endif>(UTC+04:00) Moscow</option>
                                        <option value="Asia/Muscat" @if($user->timezone == "Asia/Muscat") selected @endif>(UTC+04:00) Muscat</option>
                                        <option value="Europe/Moscow" @if($user->timezone == "Europe/Moscow") selected @endif>(UTC+04:00) St. Petersburg</option>
                                        <option value="Asia/Tbilisi" @if($user->timezone == "Asia/Tbilisi") selected @endif>(UTC+04:00) Tbilisi</option>
                                        <option value="Asia/Yerevan" @if($user->timezone == "Asia/Yerevan") selected @endif>(UTC+04:00) Yerevan</option>
                                        <option value="Asia/Kabul" @if($user->timezone == "Asia/Kabul") selected @endif>(UTC+04:30) Kabul</option>
                                        <option value="Asia/Karachi" @if($user->timezone == "Asia/Karachi") selected @endif>(UTC+05:00) Islamabad</option>
                                        <option value="Asia/Karachi" @if($user->timezone == "Asia/Karachi") selected @endif>(UTC+05:00) Karachi</option>
                                        <option value="Asia/Tashkent" @if($user->timezone == "Asia/Tashkent") selected @endif>(UTC+05:00) Tashkent</option>
                                        <option value="Asia/Calcutta" @if($user->timezone == "Asia/Calcutta") selected @endif>(UTC+05:30) Chennai</option>
                                        <option value="Asia/Kolkata" @if($user->timezone == "Asia/Kolkata") selected @endif>(UTC+05:30) Kolkata</option>
                                        <option value="Asia/Calcutta" @if($user->timezone == "Asia/Calcutta") selected @endif>(UTC+05:30) Mumbai</option>
                                        <option value="Asia/Calcutta" @if($user->timezone == "Asia/Calcutta") selected @endif>(UTC+05:30) New Delhi</option>
                                        <option value="Asia/Calcutta" @if($user->timezone == "Asia/Calcutta") selected @endif>(UTC+05:30) Sri Jayawardenepura</option>
                                        <option value="Asia/Katmandu" @if($user->timezone == "Asia/Katmandu") selected @endif>(UTC+05:45) Kathmandu</option>
                                        <option value="Asia/Almaty" @if($user->timezone == "Asia/Almaty") selected @endif>(UTC+06:00) Almaty</option>
                                        <option value="Asia/Dhaka" @if($user->timezone == "Asia/Dhaka") selected @endif>(UTC+06:00) Astana</option>
                                        <option value="Asia/Dhaka" @if($user->timezone == "Asia/Dhaka") selected @endif>(UTC+06:00) Dhaka</option>
                                        <option value="Asia/Yekaterinburg" @if($user->timezone == "Asia/Yekaterinburg") selected @endif>(UTC+06:00) Ekaterinburg</option>
                                        <option value="Asia/Rangoon" @if($user->timezone == "Asia/Rangoon") selected @endif>(UTC+06:30) Rangoon</option>
                                        <option value="Asia/Bangkok" @if($user->timezone == "Asia/Bangkok") selected @endif>(UTC+07:00) Bangkok</option>
                                        <option value="Asia/Bangkok" @if($user->timezone == "Asia/Bangkok") selected @endif>(UTC+07:00) Hanoi</option>
                                        <option value="Asia/Jakarta" @if($user->timezone == "Asia/Jakarta") selected @endif>(UTC+07:00) Jakarta</option>
                                        <option value="Asia/Novosibirsk" @if($user->timezone == "Asia/Novosibirsk") selected @endif>(UTC+07:00) Novosibirsk</option>
                                        <option value="Asia/Hong_Kong" @if($user->timezone == "Asia/Hong_Kong") selected @endif>(UTC+08:00) Beijing</option>
                                        <option value="Asia/Chongqing" @if($user->timezone == "Asia/Chongqing") selected @endif>(UTC+08:00) Chongqing</option>
                                        <option value="Asia/Hong_Kong" @if($user->timezone == "Asia/Hong_Kong") selected @endif>(UTC+08:00) Hong Kong</option>
                                        <option value="Asia/Krasnoyarsk" @if($user->timezone == "Asia/Krasnoyarsk") selected @endif>(UTC+08:00) Krasnoyarsk</option>
                                        <option value="Asia/Kuala_Lumpur" @if($user->timezone == "Asia/Kuala_Lumpur") selected @endif>(UTC+08:00) Kuala Lumpur</option>
                                        <option value="Australia/Perth" @if($user->timezone == "Australia/Perth") selected @endif>(UTC+08:00) Perth</option>
                                        <option value="Asia/Singapore" @if($user->timezone == "Asia/Singapore") selected @endif>(UTC+08:00) Singapore</option>
                                        <option value="Asia/Taipei" @if($user->timezone == "Asia/Taipei") selected @endif>(UTC+08:00) Taipei</option>
                                        <option value="Asia/Ulan_Bator" @if($user->timezone == "Asia/Ulan_Bator") selected @endif>(UTC+08:00) Ulaan Bataar</option>
                                        <option value="Asia/Urumqi" @if($user->timezone == "Asia/Urumqi") selected @endif>(UTC+08:00) Urumqi</option>
                                        <option value="Asia/Irkutsk" @if($user->timezone == "Asia/Irkutsk") selected @endif>(UTC+09:00) Irkutsk</option>
                                        <option value="Asia/Tokyo" @if($user->timezone == "Asia/Tokyo") selected @endif>(UTC+09:00) Osaka</option>
                                        <option value="Asia/Tokyo" @if($user->timezone == "Asia/Tokyo") selected @endif>(UTC+09:00) Sapporo</option>
                                        <option value="Asia/Seoul" @if($user->timezone == "Asia/Seoul") selected @endif>(UTC+09:00) Seoul</option>
                                        <option value="Asia/Tokyo" @if($user->timezone == "Asia/Tokyo") selected @endif>(UTC+09:00) Tokyo</option>
                                        <option value="Australia/Adelaide" @if($user->timezone == "Australia/Adelaide") selected @endif>(UTC+09:30) Adelaide</option>
                                        <option value="Australia/Darwin" @if($user->timezone == "Australia/Darwin") selected @endif>(UTC+09:30) Darwin</option>
                                        <option value="Australia/Brisbane" @if($user->timezone == "Australia/Brisbane") selected @endif>(UTC+10:00) Brisbane</option>
                                        <option value="Australia/Canberra" @if($user->timezone == "Australia/Canberra") selected @endif>(UTC+10:00) Canberra</option>
                                        <option value="Pacific/Guam" @if($user->timezone == "Pacific/Guam") selected @endif>(UTC+10:00) Guam</option>
                                        <option value="Australia/Hobart" @if($user->timezone == "Australia/Hobart") selected @endif>(UTC+10:00) Hobart</option>
                                        <option value="Australia/Melbourne" @if($user->timezone == "Australia/Melbourne") selected @endif>(UTC+10:00) Melbourne</option>
                                        <option value="Pacific/Port_Moresby" @if($user->timezone == "Pacific/Port_Moresby") selected @endif>(UTC+10:00) Port Moresby</option>
                                        <option value="Australia/Sydney" @if($user->timezone == "Australia/Sydney") selected @endif>(UTC+10:00) Sydney</option>
                                        <option value="Asia/Yakutsk" @if($user->timezone == "Asia/Yakutsk") selected @endif>(UTC+10:00) Yakutsk</option>
                                        <option value="Asia/Vladivostok" @if($user->timezone == "Asia/Vladivostok") selected @endif>(UTC+11:00) Vladivostok</option>
                                        <option value="Pacific/Auckland" @if($user->timezone == "Pacific/Auckland") selected @endif>(UTC+12:00) Auckland</option>
                                        <option value="Pacific/Fiji" @if($user->timezone == "Pacific/Fiji") selected @endif>(UTC+12:00) Fiji</option>
                                        <option value="Pacific/Kwajalein" @if($user->timezone == "Pacific/Kwajalein") selected @endif>(UTC+12:00) International Date Line West</option>
                                        <option value="Asia/Kamchatka" @if($user->timezone == "Asia/Kamchatka") selected @endif>(UTC+12:00) Kamchatka</option>
                                        <option value="Asia/Magadan" @if($user->timezone == "Asia/Magadan") selected @endif>(UTC+12:00) Magadan</option>
                                        <option value="Pacific/Fiji" @if($user->timezone == "Pacific/Fiji") selected @endif>(UTC+12:00) Marshall Is.</option>
                                        <option value="Asia/Magadan" @if($user->timezone == "Asia/Magadan") selected @endif>(UTC+12:00) New Caledonia</option>
                                        <option value="Asia/Magadan" @if($user->timezone == "Asia/Magadan") selected @endif>(UTC+12:00) Solomon Is.</option>
                                        <option value="Pacific/Auckland" @if($user->timezone == "Pacific/Auckland") selected @endif>(UTC+12:00) Wellington</option>
                                        <option value="Pacific/Tongatapu" @if($user->timezone == "Pacific/Tongatapu") selected @endif>(UTC+13:00) Nuku"alofa</option>
                                    </select>
                                    <label for="timezone">@choice('labels.timezone', 2)</label>
                                </div>
                                <div class="input-field col s12 m6">
                                    <select name="language">
                                        <option value="de" @if($user->locale == 'de')selected="selected"@endif>@lang('languages.german')</option>
                                        <option value="en" @if($user->locale == 'en')selected="selected"@endif>@lang('languages.english')</option>
                                    </select>
                                    <label for="language">@lang('labels.language')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <select name="notification_type">
                                        <option></option>
                                        <option value="Telegram" @if($user->setting('notification_type') == 'Telegram')selected="selected"@endif>Telegram</option>
                                    </select>
                                    <label for="notification_type">@lang('labels.notification_type')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <div class="switch">
                                        <label>
                                            @lang('labels.off')
                                            <input name="notifications_enabled" type="checkbox"
                                                   @if($user->setting('notifications_enabled') == true) checked @endif>
                                            <span class="lever"></span>
                                            @lang('labels.on') @choice('labels.notifications', 2)
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <div class="switch">
                                        <label>
                                            @lang('labels.off')
                                            <input name="notifications_enabled" type="checkbox"
                                               @if($user->setting('auto_nightmode_enabled') == true) checked @endif>
                                            <span class="lever"></span>
                                            @lang('labels.on') @lang('labels.auto_nightmode')
                                        </label>
                                    </div>
                                </div>
                            </div>

                            @if(Gate::allows('admin'))
                                <div class="row"><br /></div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <select name="abilities[]" multiple>
                                            <option disabled @if(is_null(\App\UserAbility::abilities())) selected @endif></option>
                                            @foreach (\App\UserAbility::abilities() as $a)
                                                <option value="{{ $a }}" @if($user->ability($a) == true)selected="selected"@endif>{{ $a }}</option>
                                            @endforeach
                                        </select>
                                        <label for="abilities[]">@choice('labels.abilities', 2)</label>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit">@lang('buttons.save')
                                        <i class="material-icons right">send</i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/user_settings/' . $user->settingId('notifications_telegram_chat_id')) }}" data-method="DELETE"
                          data-redirect-success="{{ url('users/' . $user->id . '/edit') }}">
                        <div class="card-content">
                            <span class="card-title activator grey-text text-darken-4 truncate">
                                <span>Setup Telegram</span>
                            </span>

                            @if (!is_null($user->setting('notifications_telegram_chat_id')))
                                <div class="card-panel teal lighten-2">@lang('messages.users.setup_telegram_ok')</div>
                            @else
                                <div class="card-panel red lighten-2">@lang('messages.users.setup_telegram_err')</div>
                            @endif

                        </div>
                        <div class="card-action">
                            <div class="row">
                                <div class="input-field col s12">
                                    @if (!is_null($user->setting('notifications_telegram_chat_id')))<input hidden name="user_id" value="{{ Auth::user()->id }}">
                                    <button class="btn waves-effect waves-light red" type="submit">@lang('buttons.delete')
                                        <i class="material-icons right">send</i>
                                    </button>
                                    @else
                                        <a href="{{ url('users/setup/telegram') }}" class="btn waves-effect waves-light teal">@lang('buttons.start_setup')</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/users/' . $user->id) }}" data-method="PUT"
                          data-redirect-success="{{ url('/') }}">
                        <div class="card-content">

                            <span class="card-title activator grey-text text-darken-4 truncate">
                                <span>@lang('labels.password')</span>
                            </span>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input class="validate" required type="password" placeholder="@lang('labels.password')" name="password" value="">
                                    <label for="password">@lang('labels.password')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <input class="validate" required type="password" placeholder="@lang('labels.password')" name="password_2" value="">
                                    <label for="password_2">@lang('labels.password')</label>
                                </div>
                            </div>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit">@lang('buttons.save')
                                        <i class="material-icons right">send</i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed-action-btn">
        <a class="btn-floating btn-large teal">
            <i class="large material-icons">mode_edit</i>
        </a>
        <ul>
            <li><a class="btn-floating teal" href="/users/{{ $user->id }}"><i class="material-icons">info</i></a></li>
            <li><a class="btn-floating red" href="/users/{{ $user->id }}/delete"><i class="material-icons">delete</i></a></li>
            <li><a class="btn-floating green" href="/users/create"><i class="material-icons">add</i></a></li>
        </ul>
    </div>
@stop