<?php

use Illuminate\Http\Request;


Route::post('apiai', 'ApiAiController@webhook');
Route::post('apiai/send_request', 'ApiAiController@parseAndSendRequest');
