<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// simple health endpoint (used by bootstrap health check)
Route::get('/up', function () {
    return response('OK', 200);
});
