<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('apiai', 'ApiAiController@webhook');
Route::post('apiai/send_request', 'ApiAiController@parseAndSendRequest');

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
Route::delete('files/associate/{source_type}/{source_id}/{file_id}', 'FileController@associate_delete');
Route::post('files/associate/{source_type}/{source_id}', 'FileController@associate');

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
Route::post('action_sequence_schedules/{id}/skip', 'ActionSequenceScheduleController@skip');

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
