<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;

Route::middleware(['throttle:global'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
});
