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

Route::group(['prefix' => '/dashboard'], function () {
    Route::view('/', 'index')->name('dashboard');
    Route::group(['prefix'=>'/news'], function () {

        Route::post('/create', 'NewsController@store');

//        Route::delete('/{id}', 'NewsAdminController@destroy')->name('news.delete');

        Route::get('/list', 'NewsAdminController@index')->name('news.list');
        Route::view('/', 'pages.news.news')->name('news.news');

        Route::view('/create', 'pages.news.news-form')->name('news.form');
    });
});