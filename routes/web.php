<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome'); // Which is now the login page
});

Route::get('/dashboard', function () {
    return view('dashboard');
});
