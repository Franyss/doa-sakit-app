<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoaSakitController;

Route::resource('doa-sakit', DoaSakitController::class);
Route::get('/', function () {
    return view('welcome');
});
