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

Route::post('/', function () {
    return json_encode("hello");
});


Route::post("/fetch/getSharedUsers", "App\Http\Controllers\ApiController@getSharedUsers");

Route::post("/fetch/Login", "App\Http\Controllers\ApiController@loginCheckController");

Route::post("/fetch/Register", "App\Http\Controllers\ApiController@registerCheckController");

Route::post("/fetch/checkLogin", "App\Http\Controllers\ApiController@checkLogin");

Route::post("/fetch/getTasks", "App\Http\Controllers\TasksController@getTasks");

Route::post("/fetch/addTask", "App\Http\Controllers\TasksController@addTask");

Route::post("/fetch/deleteTask", "App\Http\Controllers\TasksController@deleteTask");

Route::post("/fetch/checkDone", "App\Http\Controllers\TasksController@checkDone");

Route::post("/fetch/changeShare", "App\Http\Controllers\TasksController@changeShare");

Route::post("/fetch/editTask", "App\Http\Controllers\TasksController@editTask");
