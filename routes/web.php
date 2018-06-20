<?php

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

Route::get('/', function () {
    return redirect('index');

});

Route::middleware(['web']) -> group(function () {
    //区域查找url

//    Route::any('test', 'CurlController@start');
    Route::any('choose', 'CurlController@start');
    Route::any('index', 'CurlController@showindex');
    Route::any('ajaxRefresh', 'CurlController@ajaxRefresh');
});
