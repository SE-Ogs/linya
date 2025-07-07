<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layout.user');
});

Route::get('/dashboard', function(){
    return view('layout.user');
});
