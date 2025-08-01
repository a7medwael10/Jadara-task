<?php

use App\Http\Controllers\PostDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('posts', PostDashboardController::class);
