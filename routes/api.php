<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DoaSakitController;

Route::name('api.')->group(function () {
    Route::apiResource('doa-sakit', DoaSakitController::class);
});
