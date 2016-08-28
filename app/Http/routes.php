<?php
use Illuminate\Support\Facades\Broadcast;

Route::get('/', 'DashboardController@show');

Route::get('/terraria', 'ViewController@terraria');
Route::get('/terraria/create', 'ViewController@terrariaCreate');
Route::get('/terraria/{id}/edit', 'ViewController@terrariaEdit');
Route::get('/terraria/{id}/delete', 'ViewController@terrariaDelete');
Route::get('/terraria/{id}', 'ViewController@terrariaShow');

Route::get('/animals', 'ViewController@animals');
Route::get('/animals/create', 'ViewController@animalsCreate');
Route::get('/animals/{id}/edit', 'ViewController@animalsEdit');
Route::get('/animals/{id}/delete', 'ViewController@animalsDelete');
Route::get('/animals/{id}', 'ViewController@animalsShow');

Route::get('/pumps', 'ViewController@pumps');
Route::get('/pumps/create', 'ViewController@pumpsCreate');
Route::get('/pumps/{id}/edit', 'ViewController@pumpsEdit');
Route::get('/pumps/{id}/delete', 'ViewController@pumpsDelete');
Route::get('/pumps/{id}', 'ViewController@pumpsShow');

Route::get('/valves', 'ViewController@valves');
Route::get('/valves/create', 'ViewController@valvesCreate');
Route::get('/valves/{id}/edit', 'ViewController@valvesEdit');
Route::get('/valves/{id}/delete', 'ViewController@valvesDelete');
Route::get('/valves/{id}', 'ViewController@valvesShow');

Route::get('/controlunits', 'ViewController@controlunits');
Route::get('/controlunits/create', 'ViewController@controlunitsCreate');
Route::get('/controlunits/{id}/edit', 'ViewController@controlunitsEdit');
Route::get('/controlunits/{id}/delete', 'ViewController@controlunitsDelete');
Route::get('/controlunits/{id}', 'ViewController@controlunitsShow');

Route::get('/logical_sensors', 'ViewController@logical_sensors');
Route::get('/logical_sensors/create', 'ViewController@logical_sensorsCreate');
Route::get('/logical_sensors/{id}/edit', 'ViewController@logical_sensorsEdit');
Route::get('/logical_sensors/{id}/delete', 'ViewController@logical_sensorsDelete');
Route::get('/logical_sensors/{id}', 'ViewController@logical_sensorsShow');

Route::get('/physical_sensors', 'ViewController@physical_sensors');
Route::get('/physical_sensors/create', 'ViewController@physical_sensorsCreate');
Route::get('/physical_sensors/{id}/edit', 'ViewController@physical_sensorsEdit');
Route::get('/physical_sensors/{id}/delete', 'ViewController@physical_sensorsDelete');
Route::get('/physical_sensors/{id}', 'ViewController@physical_sensorsShow');

Route::get('/files', 'ViewController@files');
Route::get('/files/create', 'ViewController@filesCreate');
Route::get('/files/{id}/edit', 'ViewController@filesEdit');
Route::get('/files/{id}/delete', 'ViewController@filesDelete');
Route::get('/files/{id}/download', 'ViewController@filesDownload');
Route::get('/files/{id}', 'ViewController@filesShow');

Route::get('/users', 'ViewController@users');
Route::get('/users/create', 'ViewController@usersCreate');
Route::get('/users/{id}/edit', 'ViewController@usersEdit');
Route::get('/users/{id}/delete', 'ViewController@usersDelete');
Route::get('/users/setup/telegram', 'ViewController@usersSetupTelegram');
Route::get('/users/{id}', 'ViewController@usersShow');

Route::get('/logs', 'ViewController@logs');
Route::get('/logs/create', 'ViewController@logsCreate');
Route::get('/logs/{id}/edit', 'ViewController@logsEdit');
Route::get('/logs/{id}/delete', 'ViewController@logsDelete');
Route::get('/logs/{id}', 'ViewController@logsShow');

Route::get('/logical_sensor_thresholds', 'ViewController@logical_sensor_thresholds');
Route::get('/logical_sensor_thresholds/create', 'ViewController@logical_sensor_thresholdsCreate');
Route::get('/logical_sensor_thresholds/{id}/edit', 'ViewController@logical_sensor_thresholdsEdit');
Route::get('/logical_sensor_thresholds/{id}/delete', 'ViewController@logical_sensor_thresholdsDelete');
Route::get('/logical_sensor_thresholds/{id}', 'ViewController@logical_sensor_thresholdsShow');

Route::get('/actions', 'ViewController@actions');
Route::get('/actions/create', 'ViewController@actionsCreate');
Route::get('/actions/{id}/edit', 'ViewController@actionsEdit');
Route::get('/actions/{id}/delete', 'ViewController@actionsDelete');
Route::get('/actions/{id}', 'ViewController@actionsShow');

Route::get('/action_sequences', 'ViewController@action_sequences');
Route::get('/action_sequences/create', 'ViewController@action_sequencesCreate');
Route::get('/action_sequences/{id}/edit', 'ViewController@action_sequencesEdit');
Route::get('/action_sequences/{id}/delete', 'ViewController@action_sequencesDelete');
Route::get('/action_sequences/{id}', 'ViewController@action_sequencesShow');

Route::get('/action_sequence_schedules', 'ViewController@action_sequence_schedules');
Route::get('/action_sequence_schedules/create', 'ViewController@action_sequence_schedulesCreate');
Route::get('/action_sequence_schedules/{id}/edit', 'ViewController@action_sequence_schedulesEdit');
Route::get('/action_sequence_schedules/{id}/delete', 'ViewController@action_sequence_schedulesDelete');
Route::get('/action_sequence_schedules/{id}', 'ViewController@action_sequence_schedulesShow');

/*
 * Depracted since 5.3
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');
*/

/*
New since 5.3
*/
Route::post('broadcasting/auth', 'BroadcastController@authenticate');

Route::group(['prefix' => 'auth'], function() {
    Auth::routes();
});

Route::group(['prefix' => 'api/v1'], function() {
    Route::resource('animals', 'AnimalController');
    Route::resource('terraria', 'TerrariumController');
    Route::get('terraria/{id}/sensorreadings', 'TerrariumController@sensorreadings');
    Route::get('terraria/{id}/sensorreadingsByType/{type}', 'TerrariumController@sensorreadingsByType');
    Route::resource('controlunits', 'ControlunitController');
    Route::get('controlunits/{id}/fetch_desired_states', 'ControlunitController@fetchDesiredStates');
    Route::resource('pumps', 'PumpController');
    Route::resource('valves', 'ValveController');
    Route::resource('physical_sensors', 'PhysicalSensorController');
    Route::resource('logical_sensors', 'LogicalSensorController');
    Route::resource('logical_sensor_thresholds', 'LogicalSensorThresholdController');
    Route::post('logical_sensor_thresholds/copy', 'LogicalSensorThresholdController@copy');
    Route::resource('sensorreadings', 'SensorreadingController');
    Route::resource('files', 'FileController');
    Route::resource('file_properties', 'FilePropertyController');
    Route::resource('critical_states', 'CriticalStateController');
    Route::post('critical_states/evaluate', 'CriticalStateController@evaluate');
    Route::resource('users', 'UserController');
    Route::get('users/{id}/setting/{setting_name}', 'UserController@setting');
    Route::resource('user_settings', 'UserSettingController');
    Route::post('telegram/' . env('TELEGRAM_WEBHOOK_TOKEN'), 'TelegramController@handle');
    Route::resource('actions', 'ActionController');
    Route::resource('action_sequences', 'ActionSequenceController');
    Route::resource('action_sequence_schedules', 'ActionSequenceScheduleController');
});