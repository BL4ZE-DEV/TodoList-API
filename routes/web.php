<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/register', function() {
    return view('Auth.register');
});


Route::get('/login', function ()  {
   return view('Auth.login'); 
});

Route::get('todo', function() {
    return view('todo');
});
