<?php

use App\Http\Controllers\Script\MainController as ScriptController;
use App\Http\Controllers\Widget\MainController as WidgetController;
use Illuminate\Support\Facades\Route;

Route::controller(ScriptController::class)->group(function () {
    Route::get('/settings', 'index');
    Route::post('settings/update', 'update');
});

Route::controller(WidgetController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('widget/update', 'update');
    Route::get('widget/refresh', 'refresh');
});
