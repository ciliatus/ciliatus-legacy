@extends('master')

@section('breadcrumbs')
    <a href="/biography_entries" class="breadcrumb">@choice('components.biography_entries', 2)</a>
    <a href="/biography_entries/types" class="breadcrumb">@lang('labels.type')</a>
    <a href="/biography_entries/types/create" class="breadcrumb">@lang('buttons.create')</a>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 m12 l6">
                <div class="card">
                    <form action="{{ url('api/v1/biography_entries/categories') }}" data-method="POST" data-redirect-success="auto">
                        <div class="card-content">

                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" placeholder="@lang('labels.name')" name="name" value="">
                                    <label for="name">@lang('labels.name')</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="input-field col s12">
                                    <select class="icons" name="icon">
                                        <option value="add_alert">add_alert</option>
                                        <option value="airplay">airplay</option>
                                        <option value="alarm_off">alarm_off</option>
                                        <option value="alarm_on">alarm_on</option>
                                        <option value="album">album</option>
                                        <option value="android">android</option>
                                        <option value="announcement">announcement</option>
                                        <option value="aspect_ratio">aspect_ratio</option>
                                        <option value="assessment">assessment</option>
                                        <option value="assignment">assignment</option>
                                        <option value="assignment_ind">assignment_ind</option>
                                        <option value="assignment_late">assignment_late</option>
                                        <option value="av_timer">av_timer</option>
                                        <option value="business">business</option>
                                        <option value="call">call</option>
                                        <option value="call_end">call_end</option>
                                        <option value="call_made">call_made</option>
                                        <option value="call_merge">call_merge</option>
                                        <option value="call_missed">call_missed</option>
                                        <option value="call_received">call_received</option>
                                        <option value="call_split">call_split</option>
                                        <option value="chat">chat</option>
                                        <option value="chat_bubble">chat_bubble</option>
                                        <option value="chat_bubble_outline">chat_bubble_outline</option>
                                        <option value="class">class</option>
                                        <option value="clear_all">clear_all</option>
                                        <option value="closed_caption">closed_caption</option>
                                        <option value="code">code</option>
                                        <option value="comment">comment</option>
                                        <option value="contact_phone">contact_phone</option>
                                        <option value="contacts">contacts</option>
                                        <option value="credit_card">credit_card</option>
                                        <option value="dashboard">dashboard</option>
                                        <option value="delete">delete</option>
                                        <option value="description">description</option>
                                        <option value="dialer_sip">dialer_sip</option>
                                        <option value="dialpad">dialpad</option>
                                        <option value="dns">dns</option>
                                        <option value="done">done</option>
                                        <option value="done_all">done_all</option>
                                        <option value="email">email</option>
                                        <option value="equalizer">equalizer</option>
                                        <option value="error">error</option>
                                        <option value="error_outline">error_outline</option>
                                        <option value="explicit">explicit</option>
                                        <option value="fast_forward">fast_forward</option>
                                        <option value="fast_rewind">fast_rewind</option>
                                        <option value="forum">forum</option>
                                        <option value="forward_10">forward_10</option>
                                        <option value="forward_30">forward_30</option>
                                        <option value="forward_5">forward_5</option>
                                        <option value="games">games</option>
                                        <option value="grade">grade</option>
                                        <option value="group_work">group_work</option>
                                        <option value="hd">hd</option>
                                        <option value="hearing">hearing</option>
                                        <option value="high_quality">high_quality</option>
                                        <option value="http">http</option>
                                        <option value="https">https</option>
                                        <option value="import_export">import_export</option>
                                        <option value="info">info</option>
                                        <option value="info_outline">info_outline</option>
                                        <option value="input">input</option>
                                        <option value="invert_colors">invert_colors</option>
                                        <option value="invert_colors_off">invert_colors_off</option>
                                        <option value="label">label</option>
                                        <option value="label_outline">label_outline</option>
                                        <option value="language">language</option>
                                        <option value="launch">launch</option>
                                        <option value="library_add">library_add</option>
                                        <option value="library_books">library_books</option>
                                        <option value="library_music">library_music</option>
                                        <option value="list">list</option>
                                        <option value="live_help">live_help</option>
                                        <option value="location_off">location_off</option>
                                        <option value="location_on">location_on</option>
                                        <option value="lock">lock</option>
                                        <option value="lock_open">lock_open</option>
                                        <option value="lock_outline">lock_outline</option>
                                        <option value="loop">loop</option>
                                        <option value="loyalty">loyalty</option>
                                        <option value="markunread_mailbox">markunread_mailbox</option>
                                        <option value="message">message</option>
                                        <option value="mic">mic</option>
                                        <option value="mic_none">mic_none</option>
                                        <option value="mic_off">mic_off</option>
                                        <option value="mode_edit">mode_edit</option>
                                        <option value="movie">movie</option>
                                        <option value="my_location">my_location</option>
                                        <option value="navigation">navigation</option>
                                        <option value="new_releases">new_releases</option>
                                        <option value="no_sim">no_sim</option>
                                        <option value="not_interested">not_interested</option>
                                        <option value="note_add">note_add</option>
                                        <option value="offline_pin">offline_pin</option>
                                        <option value="open_in_browser">open_in_browser</option>
                                        <option value="open_in_new">open_in_new</option>
                                        <option value="open_with">open_with</option>
                                        <option value="pageview">pageview</option>
                                        <option value="pause">pause</option>
                                        <option value="pause_circle_filled">pause_circle_filled</option>
                                        <option value="pause_circle_outline">pause_circle_outline</option>
                                        <option value="payment">payment</option>
                                        <option value="perm_camera_mic">perm_camera_mic</option>
                                        <option value="perm_contact_calendar">perm_contact_calendar</option>
                                        <option value="perm_data_setting">perm_data_setting</option>
                                        <option value="perm_device_information">perm_device_information</option>
                                        <option value="perm_identity">perm_identity</option>
                                        <option value="perm_media">perm_media</option>
                                        <option value="perm_phone_msg">perm_phone_msg</option>
                                        <option value="perm_scan_wifi">perm_scan_wifi</option>
                                        <option value="person_pin">person_pin</option>
                                        <option value="phone">phone</option>
                                        <option value="phonelink_erase">phonelink_erase</option>
                                        <option value="phonelink_lock">phonelink_lock</option>
                                        <option value="phonelink_ring">phonelink_ring</option>
                                        <option value="phonelink_setup">phonelink_setup</option>
                                        <option value="picture_in_picture">picture_in_picture</option>
                                        <option value="play_arrow">play_arrow</option>
                                        <option value="play_circle_filled">play_circle_filled</option>
                                        <option value="play_circle_outline">play_circle_outline</option>
                                        <option value="play_for_work">play_for_work</option>
                                        <option value="playlist_add">playlist_add</option>
                                        <option value="polymer">polymer</option>
                                        <option value="portable_wifi_off">portable_wifi_off</option>
                                        <option value="power_settings_new">power_settings_new</option>
                                        <option value="present_to_all">present_to_all</option>
                                        <option value="print">print</option>
                                        <option value="query_builder">query_builder</option>
                                        <option value="question_answer">question_answer</option>
                                        <option value="queue">queue</option>
                                        <option value="queue_music">queue_music</option>
                                        <option value="radio">radio</option>
                                        <option value="receipt">receipt</option>
                                        <option value="recent_actors">recent_actors</option>
                                        <option value="redeem">redeem</option>
                                        <option value="reorder">reorder</option>
                                        <option value="repeat">repeat</option>
                                        <option value="repeat_one">repeat_one</option>
                                        <option value="replay">replay</option>
                                        <option value="replay_10">replay_10</option>
                                        <option value="replay_30">replay_30</option>
                                        <option value="replay_5">replay_5</option>
                                        <option value="report_problem">report_problem</option>
                                        <option value="restore">restore</option>
                                        <option value="ring_volume">ring_volume</option>
                                        <option value="room">room</option>
                                        <option value="schedule">schedule</option>
                                        <option value="search">search</option>
                                        <option value="settings">settings</option>
                                        <option value="settings_applications">settings_applications</option>
                                        <option value="settings_backup_restore">settings_backup_restore</option>
                                        <option value="settings_bluetooth">settings_bluetooth</option>
                                        <option value="settings_brightness">settings_brightness</option>
                                        <option value="settings_cell">settings_cell</option>
                                        <option value="settings_ethernet">settings_ethernet</option>
                                        <option value="settings_input_antenna">settings_input_antenna</option>
                                        <option value="settings_input_component">settings_input_component</option>
                                        <option value="settings_input_composite">settings_input_composite</option>
                                        <option value="settings_input_hdmi">settings_input_hdmi</option>
                                        <option value="settings_input_svideo">settings_input_svideo</option>
                                        <option value="settings_overscan">settings_overscan</option>
                                        <option value="settings_phone">settings_phone</option>
                                        <option value="settings_power">settings_power</option>
                                        <option value="settings_remote">settings_remote</option>
                                        <option value="settings_voice">settings_voice</option>
                                        <option value="shop">shop</option>
                                        <option value="shop_two">shop_two</option>
                                        <option value="shopping_basket">shopping_basket</option>
                                        <option value="shopping_cart">shopping_cart</option>
                                        <option value="shuffle">shuffle</option>
                                        <option value="skip_next">skip_next</option>
                                        <option value="skip_previous">skip_previous</option>
                                        <option value="snooze">snooze</option>
                                        <option value="sort_by_alpha">sort_by_alpha</option>
                                        <option value="speaker_notes">speaker_notes</option>
                                        <option value="speaker_phone">speaker_phone</option>
                                        <option value="spellcheck">spellcheck</option>
                                        <option value="star">star</option>
                                        <option value="stars">stars</option>
                                        <option value="stay_current_landscape">stay_current_landscape</option>
                                        <option value="stay_current_portrait">stay_current_portrait</option>
                                        <option value="stay_primary_landscape">stay_primary_landscape</option>
                                        <option value="stay_primary_portrait">stay_primary_portrait</option>
                                        <option value="stop">stop</option>
                                        <option value="store">store</option>
                                        <option value="subject">subject</option>
                                        <option value="subtitles">subtitles</option>
                                        <option value="supervisor_account">supervisor_account</option>
                                        <option value="surround_sound">surround_sound</option>
                                        <option value="swap_calls">swap_calls</option>
                                        <option value="swap_horiz">swap_horiz</option>
                                        <option value="swap_vert">swap_vert</option>
                                        <option value="swap_vertical_circle">swap_vertical_circle</option>
                                        <option value="system_update_alt">system_update_alt</option>
                                        <option value="tab">tab</option>
                                        <option value="tab_unselected">tab_unselected</option>
                                        <option value="textsms">textsms</option>
                                        <option value="theaters">theaters</option>
                                        <option value="thumb_down">thumb_down</option>
                                        <option value="thumb_up">thumb_up</option>
                                        <option value="thumbs_up_down">thumbs_up_down</option>
                                        <option value="toc">toc</option>
                                        <option value="today">today</option>
                                        <option value="toll">toll</option>
                                        <option value="track_changes">track_changes</option>
                                        <option value="translate">translate</option>
                                        <option value="trending_down">trending_down</option>
                                        <option value="trending_flat">trending_flat</option>
                                        <option value="trending_up">trending_up</option>
                                        <option value="turned_in">turned_in</option>
                                        <option value="turned_in_not">turned_in_not</option>
                                        <option value="verified_user">verified_user</option>
                                        <option value="video_library">video_library</option>
                                        <option value="videocam">videocam</option>
                                        <option value="videocam_off">videocam_off</option>
                                        <option value="view_agenda">view_agenda</option>
                                        <option value="view_array">view_array</option>
                                        <option value="view_carousel">view_carousel</option>
                                        <option value="view_column">view_column</option>
                                        <option value="view_day">view_day</option>
                                        <option value="view_headline">view_headline</option>
                                        <option value="view_list">view_list</option>
                                        <option value="view_module">view_module</option>
                                        <option value="view_quilt">view_quilt</option>
                                        <option value="view_stream">view_stream</option>
                                        <option value="view_week">view_week</option>
                                        <option value="visibility">visibility</option>
                                        <option value="visibility_off">visibility_off</option>
                                        <option value="voicemail">voicemail</option>
                                        <option value="volume_down">volume_down</option>
                                        <option value="volume_mute">volume_mute</option>
                                        <option value="volume_off">volume_off</option>
                                        <option value="volume_up">volume_up</option>
                                        <option value="vpn_key">vpn_key</option>
                                        <option value="warning">warning</option>
                                        <option value="web">web</option>
                                        <option value="work">work</option>
                                        <option value="youtube_searched_for">youtube_searched_for</option>
                                        <option value="zoom_in">zoom_in</option>
                                        <option value="zoom_out">zoom_out</option>
                                    </select>
                                    <label for="icon">@lang('labels.icon')</label>
                                    <p>@lang('tooltips.material_icons_list')</p>
                                </div>
                            </div>

                        </div>

                        <div class="card-action">

                            <div class="row">
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit">@lang('buttons.next')
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
@stop