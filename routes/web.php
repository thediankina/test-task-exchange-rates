<?php

use App\Http\Controllers\Script\MainController;
use App\Http\Controllers\Script\RateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::controller(MainController::class)->group(function () {
    Route::get('/settings', 'index');
    Route::post('settings/update', 'update');
});

Route::controller(RateController::class)->group(function () {
    Route::get('/rates', 'index');
});
