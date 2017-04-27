<?php
use Illuminate\Support\Facades\Broadcast;

/*
 * Analytics mode e.g. for Google Insight
 */
/*
if (Request::exists('insight_login') && Auth::guest()) {
    Auth::login(App\User::find('c4b9ebb0-25bc-11e7-aeff-11625e673cb4'));
}
*/

/*
 * Web
 * / Prefix
 *
 */
Route::group(['namespace' => 'Web', 'middleware' => 'web'], function() {

    Route::get('setup/' . env('APP_KEY'), 'SetupController@start');
    Route::get('setup/' . env('APP_KEY') . '/step/{id}', 'SetupController@step');

    Route::get('/', 'DashboardController@index');
    Route::resource('dashboard', 'DashboardController');
    Route::get('categories', 'AdminController@categories');

    /*
     * Animals
     */
    Route::resource('animals', 'AnimalController');
    Route::get('animals/{id}/delete', 'AnimalController@delete');

    // Caresheets
    Route::get('animals/{animal_id}/caresheets/{id}', 'AnimalController@show_caresheet');
    Route::get('animals/{animal_id}/caresheets/{id}/delete', 'AnimalController@delete_caresheet');
    Route::get('animals/caresheets/create', 'AnimalController@create_caresheet');
    Route::get('animals/{animal_id}/caresheets/create', 'AnimalController@create_caresheet');

    // Feedingtypes
    Route::get('animals/feedings/types', 'AnimalFeedingController@edit_types');
    Route::get('animals/feedings/types/create', 'AnimalFeedingController@create_type');

    // Feedings
    Route::resource('animals/{animal_id}/feedings', 'AnimalFeedingController');
    Route::resource('animals/{animal_id}/feeding_schedules', 'AnimalFeedingScheduleController');

    // Feeding schedules
    Route::get('animals/{animal_id}/feeding_schedules/{id}/delete', 'AnimalFeedingScheduleController@delete');
    Route::get('animal_feeding_schedules', 'AnimalFeedingScheduleController@index');

    // Weighings
    Route::resource('animals/{animal_id}/weighings', 'AnimalWeighingController');
    Route::resource('animals/{animal_id}/weighing_schedules', 'AnimalWeighingScheduleController');

    // Weighing schedules
    Route::get('animal_weighing_schedules', 'AnimalWeighingScheduleController@index');
    Route::get('animals/{animal_id}/weighing_schedules/{id}/delete', 'AnimalWeighingScheduleController@delete');

    /*
     * Terraria
     */
    Route::resource('terraria', 'TerrariumController');
    Route::get('terraria/{id}/delete', 'TerrariumController@delete');

    /*
     * Controlunits
     */
    Route::resource('controlunits', 'ControlunitController');
    Route::get('controlunits/{id}/delete', 'ControlunitController@delete');

    /*
     * Pumps
     */
    Route::resource('pumps', 'PumpController');
    Route::get('pumps/{id}/delete', 'PumpController@delete');

    /*
     * Valves
     */
    Route::resource('valves', 'ValveController');
    Route::get('valves/{id}/delete', 'ValveController@delete');

    /*
     * Physical sensors
     */
    Route::resource('physical_sensors', 'PhysicalSensorController');
    Route::get('physical_sensors/{id}/delete', 'PhysicalSensorController@delete');

    /*
     * Logical sensors
     */
    Route::resource('logical_sensors', 'LogicalSensorController');
    Route::get('logical_sensors/{id}/delete', 'LogicalSensorController@delete');

    // Logical sensor thresholds
    Route::resource('logical_sensor_thresholds', 'LogicalSensorThresholdController');
    Route::get('logical_sensor_thresholds/{id}/delete', 'LogicalSensorThresholdController@delete');

    /*
     * Generic components
     */
    Route::resource('generic_components', 'GenericComponentController');
    Route::get('generic_components/{id}/delete', 'GenericComponentController@delete');
    Route::resource('generic_component_types', 'GenericComponentTypeController');
    Route::get('generic_component_types/{id}/delete', 'GenericComponentTypeController@delete');

    /*
     * Sensorreadings
     */
    Route::resource('sensorreadings', 'SensorreadingController');
    Route::get('sensorreadings/{id}/delete', 'SensorreadingController@delete');

    /*
     * Files
     */
    Route::resource('files', 'FileController');
    Route::get('files/{id}/delete', 'FileController@delete');
    Route::get('files/{id}/download/{display_name?}', 'FileController@download');

    // file properties
    Route::resource('file_properties', 'PropertyController');
    Route::get('file_properties/{id}/delete', 'PropertyController@delete');

    /*
     * Critical States
     */
    Route::resource('critical_states', 'CriticalStateController');
    Route::get('critical_states/{id}/delete', 'CriticalStateController@delete');

    /*
     * Users
     */
    Route::resource('users', 'UserController');
    Route::get('users/{id}/delete', 'UserController@delete');
    Route::get('users/{id}/personal_access_tokens/create', 'UserController@create_personal_accesss_token');
    Route::get('users/setup/telegram', 'UserController@setup_Telegram');

    // User settings
    Route::resource('user_settings', 'UserSettingController');
    Route::get('user_settings/{id}/delete', 'UserSettingController@delete');

    /*
     * Actions
     */
    Route::resource('actions', 'ActionController');
    Route::get('actions/{id}/delete', 'ActionController@delete');

    // Action sequences
    Route::get('action_sequences/{id}/delete', 'ActionSequenceController@delete');
    Route::get('action_sequences/stop_all', 'ActionSequenceController@stop_all');
    Route::get('action_sequences/resume_all', 'ActionSequenceController@resume_all');
    Route::resource('action_sequences', 'ActionSequenceController');

    // Action sequence schedules
    Route::resource('action_sequence_schedules', 'ActionSequenceScheduleController');
    Route::get('action_sequence_schedules/{id}/delete', 'ActionSequenceScheduleController@delete');

    // Action sequence triggers
    Route::resource('action_sequence_triggers', 'ActionSequenceTriggerController');
    Route::get('action_sequence_triggers/{id}/delete', 'ActionSequenceTriggerController@delete');

    // Action sequence intentions
    Route::resource('action_sequence_intentions', 'ActionSequenceIntentionController');
    Route::get('action_sequence_intentions/{id}/delete', 'ActionSequenceIntentionController@delete');

    /*
     * Biography entries
     */
    Route::resource('biography_entries', 'BiographyEntryController');
    Route::get('biography_entries/{id}/delete', 'BiographyEntryController@delete');

    /*
     * Biography entry categories
     */
    Route::get('biography_entries/categories/create', 'BiographyEntryController@create_type');
    Route::get('biography_entries/categories/{id}/delete', 'BiographyEntryController@delete_type');

    /*
     * Logs
     */
    Route::resource('logs', 'LogController');


    Route::get('map', 'DashboardController@map');

});

Route::post('broadcasting/auth', 'BroadcastController@authenticate')->middleware('auth:api');

Route::group(['namespace' => 'Api', 'prefix' => 'api/v1'], function() {
    Route::post('telegram/' . env('TELEGRAM_WEBHOOK_TOKEN'), 'TelegramController@handle');
});

Route::group(['prefix' => 'auth', 'middleware' => 'web'], function() {
    Auth::routes();
});


/*
 * API
 * /api/v1/ Prefix
 *
 */

Route::group(['namespace' => 'Api', 'prefix' => 'api/v1', 'middleware' => ['localization']], function() {
    Route::post('setup/' . env('APP_KEY') . '/step/{id}', 'SetupController@step');
});

Route::group(['namespace' => 'Api', 'prefix' => 'api/v1', 'middleware' => ['auth:api', 'localization']], function() {

    Route::post('apiai', 'ApiAiController@webhook');

    Route::get('dashboard/system_status', 'DashboardController@system_status');
    Route::resource('dashboard', 'DashboardController');

    /*
     * Animals
     */
    Route::resource('animals', 'AnimalController');

    // Animal caresheets
    Route::post('animals/caresheets', 'AnimalController@store_caresheet');
    Route::get('animals/{animal_id}/caresheets', 'AnimalController@caresheets');
    Route::delete('animals/{animal_id}/caresheets/{id}', 'AnimalController@delete_caresheet');

    // Animal feeding types
    Route::get('animals/feedings/types', 'AnimalFeedingController@types');
    Route::post('animals/feedings/types', 'AnimalFeedingController@store_type');
    Route::delete('animals/feedings/types/{id}', 'AnimalFeedingController@delete_type');

    // Animal feedings
    Route::resource('animals/{animal_id}/feedings', 'AnimalFeedingController');
    Route::resource('animals/{animal_id}/feeding_schedules', 'AnimalFeedingScheduleController');

    // Animal feeding schedules
    Route::post('animals/{animal_id}/feeding_schedules/{id}/done', 'AnimalFeedingScheduleController@done');
    Route::post('animals/{animal_id}/feeding_schedules/{id}/skip', 'AnimalFeedingScheduleController@skip');

    // Animal weighings
    Route::resource('animals/{animal_id}/weighings', 'AnimalWeighingController');
    Route::resource('animals/{animal_id}/weighing_schedules', 'AnimalWeighingScheduleController');

    // Animal weighing schedules
    Route::post('animals/{animal_id}/weighing_schedules/{id}/done', 'AnimalWeighingScheduleController@done');
    Route::post('animals/{animal_id}/weighing_schedules/{id}/skip', 'AnimalWeighingScheduleController@skip');
    Route::get('animal_feeding_schedules', 'AnimalFeedingScheduleController@index');
    Route::get('animal_weighing_schedules', 'AnimalWeighingScheduleController@index');

    /*
     * Terraria
     */
    Route::resource('terraria', 'TerrariumController');
    Route::get('terraria/{id}/infrastructure', 'TerrariumController@infrastructure');
    Route::post('terraria/{id}/action_sequence', 'TerrariumController@generateActionSequence');

    // Sensorreadings
    Route::get('terraria/{id}/sensorreadings', 'TerrariumController@sensorreadings');
    Route::get('terraria/{id}/sensorreadingsByType/{type}', 'TerrariumController@sensorreadingsByType');

    /*
     * Controlunits
     */
    Route::get('controlunits/{id}/fetch_desired_states', 'ControlunitController@fetchDesiredStates');
    Route::put('controlunits/{id}/check_in', 'ControlunitController@check_in');
    Route::resource('controlunits', 'ControlunitController');

    /*
     * Pumps
     */
    Route::resource('pumps', 'PumpController');

    /*
     * Valves
     */
    Route::resource('valves', 'ValveController');

    /*
     * Physical sensors
     */
    Route::resource('physical_sensors', 'PhysicalSensorController');

    /*
     * Logical sensors
     */
    Route::resource('logical_sensors', 'LogicalSensorController');

    // logical sensor thresholds
    Route::resource('logical_sensor_thresholds/{id}/copy', 'LogicalSensorThresholdController@copy');
    Route::resource('logical_sensor_thresholds', 'LogicalSensorThresholdController');
    Route::post('logical_sensor_thresholds/copy', 'LogicalSensorThresholdController@copy');

    /*
     * Generic components
     */
    Route::resource('generic_components', 'GenericComponentController');
    Route::resource('generic_component_types', 'GenericComponentTypeController');

    /*
     * Sensorreadings
     */
    Route::resource('sensorreadings', 'SensorreadingController');

    /*
     * Files
     */
    Route::resource('files', 'FileController');

    // File properties
    Route::resource('file_properties', 'PropertyController');

    /*
     * Critical states
     */
    Route::resource('critical_states', 'CriticalStateController');
    Route::post('critical_states/evaluate', 'CriticalStateController@evaluate');

    /*
     * Users
     */
    Route::resource('users', 'UserController');
    Route::get('users/{id}/setting/{setting_name}', 'UserController@setting');
    Route::post('users/{id}/personal_access_tokens', 'UserController@store_personal_access_token');
    Route::delete('users/{id}/personal_access_tokens/{token_id}', 'UserController@delete_personal_access_token');

    // User settings
    Route::resource('user_settings', 'UserSettingController');

    /*
     * Actions
     */
    Route::resource('actions', 'ActionController');

    /*
     * Action sequences
     */
    Route::resource('action_sequences', 'ActionSequenceController');
    Route::post('action_sequences/stop_all', 'ActionSequenceController@stop_all');
    Route::post('action_sequences/resume_all', 'ActionSequenceController@resume_all');

    /*
     * Action sequence schedules
     */
    Route::resource('action_sequence_schedules', 'ActionSequenceScheduleController');

    /*
     * Action sequence triggers
     */
    Route::resource('action_sequence_triggers', 'ActionSequenceTriggerController');

    /*
     * Action sequence intentions
     */
    Route::resource('action_sequence_intentions', 'ActionSequenceIntentionController');

    /*
     * Events
     */
    Route::resource('events', 'EventController');

    /*
     * Properties
     */
    Route::post('properties/read/{target_type}/{target_id}', 'PropertyController@setReadFlag');
    Route::resource('properties', 'PropertyController');

    /*
     * Biography entries
     */
    Route::resource('biography_entries', 'BiographyEntryController');

    // Biography entry categories
    Route::get('biography_entries/categories', 'BiographyEntryController@types');
    Route::post('biography_entries/categories', 'BiographyEntryController@store_type');
    Route::delete('biography_entries/categories/{id}', 'BiographyEntryController@delete_type');

    /*
     * Logs
     */
    Route::resource('logs', 'LogController');
});