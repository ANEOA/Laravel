<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    logger()->channel('telegram')->debug('Hello! File: web.php - Logger - Telegram! ' . request()->url());
    return view('welcome');
});
