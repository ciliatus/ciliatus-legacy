<?php

Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

Route::group(['prefix' => 'api/v1'], function() {
    Route::resource('animals', 'AnimalController');
    Route::resource('terraria', 'TerrariumController');
    Route::resource('physical_sensors', 'PhysicalSensorController');
    Route::resource('logical_sensors', 'LogicalSensorController');
});