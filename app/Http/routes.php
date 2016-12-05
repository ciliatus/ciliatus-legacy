<?php
use Illuminate\Support\Facades\Broadcast;


Route::group(['namespace' => 'Web'], function() {
    Route::get('/', 'DashboardController@index');
    Route::resource('dashboard', 'DashboardController');
    Route::resource('animals', 'AnimalController');
    Route::get('animals/feedings/types', 'AnimalFeedingController@edit_types');
    Route::get('animals/feedings/types/create', 'AnimalFeedingController@create_type');
    Route::resource('animals/{id}/feedings', 'AnimalFeedingController');
    Route::resource('animals/{id}/feeding_schedules', 'AnimalFeedingScheduleController');
    Route::get('animals/{id}/delete', 'AnimalController@delete');
    Route::resource('terraria', 'TerrariumController');
    Route::get('terraria/{id}/delete', 'TerrariumController@delete');
    Route::resource('controlunits', 'ControlunitController');
    Route::get('controlunits/{id}/delete', 'ControlunitController@delete');
    Route::resource('pumps', 'PumpController');
    Route::get('pumps/{id}/delete', 'PumpController@delete');
    Route::resource('valves', 'ValveController');
    Route::get('valves/{id}/delete', 'ValveController@delete');
    Route::resource('physical_sensors', 'PhysicalSensorController');
    Route::get('physical_sensors/{id}/delete', 'PhysicalSensorController@delete');
    Route::resource('logical_sensors', 'LogicalSensorController');
    Route::get('logical_sensors/{id}/delete', 'LogicalSensorController@delete');
    Route::resource('logical_sensor_thresholds', 'LogicalSensorThresholdController');
    Route::get('logical_sensor_thresholds/{id}/delete', 'LogicalSensorThresholdController@delete');
    Route::resource('sensorreadings', 'SensorreadingController');
    Route::get('sensorreadings/{id}/delete', 'SensorreadingController@delete');
    Route::resource('files', 'FileController');
    Route::get('files/{id}/delete', 'FileController@delete');
    Route::get('files/{id}/download', 'FileController@download');
    Route::resource('file_properties', 'PropertyController');
    Route::get('file_properties/{id}/delete', 'PropertyController@delete');
    Route::resource('critical_states', 'CriticalStateController');
    Route::get('critical_states/{id}/delete', 'CriticalStateController@delete');
    Route::resource('users', 'UserController');
    Route::get('users/{id}/delete', 'UserController@delete');
    Route::get('users/setup/telegram', 'UserController@setup_Telegram');
    Route::resource('user_settings', 'UserSettingController');
    Route::get('user_settings/{id}/delete', 'UserSettingController@delete');
    Route::resource('actions', 'ActionController');
    Route::get('actions/{id}/delete', 'ActionController@delete');
    Route::resource('action_sequences', 'ActionSequenceController');
    Route::get('action_sequences/{id}/delete', 'ActionSequenceController@delete');
    Route::resource('action_sequence_schedules', 'ActionSequenceScheduleController');
    Route::get('action_sequence_schedules/{id}/delete', 'ActionSequenceScheduleController@delete');
});

Route::post('broadcasting/auth', 'BroadcastController@authenticate');

Route::group(['prefix' => 'auth'], function() {
    Auth::routes();
});

Route::group(['namespace' => 'Api', 'prefix' => 'api/v1'], function() {
    Route::resource('dashboard', 'DashboardController');
    Route::resource('animals', 'AnimalController');
    Route::post('animals/feedings/types', 'AnimalFeedingController@store_type');
    Route::resource('animals/{id}/feedings', 'AnimalFeedingController');
    Route::resource('animals/{id}/feeding_schedules', 'AnimalFeedingScheduleController');
    Route::resource('terraria', 'TerrariumController');
    Route::get('terraria/{id}/sensorreadings', 'TerrariumController@sensorreadings');
    Route::get('terraria/{id}/sensorreadingsByType/{type}', 'TerrariumController@sensorreadingsByType');
    Route::resource('controlunits', 'ControlunitController');
    Route::get('controlunits/{id}/fetch_desired_states', 'ControlunitController@fetchDesiredStates');
    Route::resource('pumps', 'PumpController');
    Route::resource('valves', 'ValveController');
    Route::resource('physical_sensors', 'PhysicalSensorController');
    Route::resource('logical_sensors', 'LogicalSensorController');
    Route::resource('logical_sensor_thresholds/{id}/copy', 'LogicalSensorThresholdController@copy');
    Route::resource('logical_sensor_thresholds', 'LogicalSensorThresholdController');
    Route::post('logical_sensor_thresholds/copy', 'LogicalSensorThresholdController@copy');
    Route::resource('sensorreadings', 'SensorreadingController');
    Route::resource('files', 'FileController');
    Route::resource('file_properties', 'PropertyController');
    Route::resource('critical_states', 'CriticalStateController');
    Route::post('critical_states/evaluate', 'CriticalStateController@evaluate');
    Route::resource('users', 'UserController');
    Route::get('users/{id}/setting/{setting_name}', 'UserController@setting');
    Route::resource('user_settings', 'UserSettingController');
    Route::post('telegram/' . env('TELEGRAM_WEBHOOK_TOKEN'), 'TelegramController@handle');
    Route::resource('actions', 'ActionController');
    Route::resource('action_sequences', 'ActionSequenceController');
    Route::resource('action_sequence_schedules', 'ActionSequenceScheduleController');
    Route::resource('events', 'EventController');
    Route::resource('properties', 'PropertyController');
});