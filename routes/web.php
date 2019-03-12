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

Route::get('/login', 'AuthAdminController@loginView');
Route::post('/login', 'AuthAdminController@login');
Route::get('/logout', 'AuthAdminController@logout');

Route::group(['prefix' => '/', 'middleware' => 'adminLogin'], function () {

    Route::get('/', 'AuthAdminController@index')->name('/');

    Route::group(['prefix' => '/news'], function () {

        Route::get('/', 'NewsAdminController@index')->name('news.list');

        Route::get('/create', 'NewsAdminController@createView')->name('form.create');
        Route::post('/create', 'NewsAdminController@store');

        Route::get('/edit/{id}', 'NewsAdminController@editView')->name('form.edit');
        Route::post('/edit/{id}', 'NewsAdminController@update');
    });
});
