<?php

Auth::routes();
Route::get('logout', 'Auth\LoginController@logout');