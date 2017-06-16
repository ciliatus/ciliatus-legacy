<?php

Route::post('telegram/' . env('TELEGRAM_WEBHOOK_TOKEN'), 'TelegramController@handle');