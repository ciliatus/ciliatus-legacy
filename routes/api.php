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

Route::get('dashboard/system_status', 'DashboardController@system_status');
Route::resource('dashboard', 'DashboardController');

Route::get('system/health', 'SystemController@health');

/*
 * Animals
 */
Route::resource('animals', 'AnimalController');
Route::get('animals/{animal_id}/files', 'AnimalController@files');

// Animal caresheets
Route::post('animals/caresheets', 'AnimalController@store_caresheet');
Route::get('animals/{animal_id}/caresheets', 'AnimalController@caresheets');
Route::delete('animals/{animal_id}/caresheets/{id}', 'AnimalController@delete_caresheet');

// Animal feeding types
Route::get('animals/feedings/types', 'AnimalFeedingEventController@types');
Route::post('animals/feedings/types', 'AnimalFeedingEventController@store_type');
Route::delete('animals/feedings/types/{id}', 'AnimalFeedingEventController@delete_type');

// Animal feedings
Route::resource('animals/{animal_id}/feedings', 'AnimalFeedingEventController');
Route::resource('animals/{animal_id}/feeding_schedules', 'AnimalFeedingSchedulePropertyController');
Route::resource('animal_feeding_schedules', 'AnimalFeedingSchedulePropertyController');

// Animal feeding schedules
Route::post('animals/{animal_id}/feeding_schedules/{id}/done', 'AnimalFeedingSchedulePropertyController@done');
Route::post('animals/{animal_id}/feeding_schedules/{id}/skip', 'AnimalFeedingSchedulePropertyController@skip');

// Animal weighings
Route::resource('animals/{animal_id}/weighings', 'AnimalWeighingEventController');
Route::resource('animals/{animal_id}/weighing_schedules', 'AnimalWeighingSchedulePropertyController');
Route::resource('animal_weighing_schedules', 'AnimalWeighingSchedulePropertyController');

// Animal weighing schedules
Route::post('animals/{animal_id}/weighing_schedules/{id}/done', 'AnimalWeighingSchedulePropertyController@done');
Route::post('animals/{animal_id}/weighing_schedules/{id}/skip', 'AnimalWeighingSchedulePropertyController@skip');
Route::get('animal_feeding_schedules', 'AnimalFeedingSchedulePropertyController@index');
Route::get('animal_weighing_schedules', 'AnimalWeighingSchedulePropertyController@index');

/*
 * Terraria
 */
Route::resource('terraria', 'TerrariumController');
Route::get('terraria/{id}/infrastructure', 'TerrariumController@infrastructure');
Route::post('terraria/{id}/action_sequence', 'TerrariumController@generateActionSequence');
Route::get('terraria/{animal_id}/files', 'TerrariumController@files');

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
Route::post('logical_sensor_thresholds/{id}/copy', 'LogicalSensorThresholdController@copy');
Route::resource('logical_sensor_thresholds', 'LogicalSensorThresholdController');

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
Route::post('files/set-background/{source_type}/{source_id}/{file_id}', 'FileController@set_background');

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
Route::post('action_sequence_triggers/{id}/skip', 'ActionSequenceTriggerController@skip');

/*
 * Action sequence intentions
 */
Route::resource('action_sequence_intentions', 'ActionSequenceIntentionController');
Route::post('action_sequence_intentions/{id}/skip', 'ActionSequenceIntentionController@skip');

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
Route::resource('biography_entries', 'BiographyEntryEventController');

// Biography entry categories
Route::get('biography_entries/categories', 'BiographyEntryEventController@types');
Route::post('biography_entries/categories', 'BiographyEntryEventController@store_type');
Route::delete('biography_entries/categories/{id}', 'BiographyEntryEventController@delete_type');

/*
 * Reminders
 */
Route::resource('reminders', 'ReminderController');

/*
 * Logs
 */
Route::resource('logs', 'LogController');
