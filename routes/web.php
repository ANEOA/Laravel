<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    logger()->channel('telegram')->debug('Hello. This Logger - Telegram!');
    return view('welcome');
});
