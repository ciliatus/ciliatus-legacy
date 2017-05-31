<?php

Route::post(env('APP_KEY') . '/step/{id}', 'SetupController@step');