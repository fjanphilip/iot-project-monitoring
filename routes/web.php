<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorExportController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/sensor/export', [SensorExportController::class, 'exportCsv']);
