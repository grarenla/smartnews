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

use Illuminate\Support\Facades\Route;

Route::view('/', 'index')->name('dashboard');
Route::group(['prefix' => '/news'], function () {

    Route::get('/', 'NewsAdminController@index')->name('news.list');

    Route::get('/create', 'NewsAdminController@createView')->name('form.create');
    Route::post('/create', 'NewsAdminController@store');

    Route::get('/edit/{id}', 'NewsAdminController@editView')->name('form.edit');
    Route::post('/edit/{id}', 'NewsAdminController@update');
//        Route::view('/create', 'pages.news.news-form')->name('news.form');
});
