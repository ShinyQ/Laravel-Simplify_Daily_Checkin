<?php

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

Route::get('/', 'CheckinController@index')->name('/');
Route::get('/status_checkin/{username}/{password}', 'CheckinController@get_status_checkin');
Route::post('/simpan_data/{id}', 'CheckinController@simpan_data');
Route::post('/checkin/{username}/{password}/{city}', 'CheckinController@checkin');
