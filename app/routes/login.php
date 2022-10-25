<?php

//로그인(토큰발행)

use Illuminate\Support\Facades\Route;

Route::get('/', 'App\Http\Controllers\LoginController@login');
