<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/news', 'NewsController@index');
Route::get('/news/{id}', 'NewsController@show');
Route::get('/category/news/{id}', 'NewsController@listByCategory');
Route::post('/news/insert', 'NewsController@store');
Route::put('/news/edit/{id}', 'NewsController@update');
Route::delete('/news/delete/{id}', 'NewsController@destroy');
Route::get('/category', 'CategoryController@index');


//Route::middleware('cors')->get('/news', 'NewsController@index');
//Route::middleware('cors')->get('/news/{id}', 'NewsController@show');
//Route::middleware('cors')->get('/category/news/{id}', 'NewsController@listByCategory');
//Route::middleware('cors')->post('/news/insert', 'NewsController@store');
//Route::middleware('cors')->put('/news/edit/{id}', 'NewsController@update');
//Route::middleware('cors')->delete('/news/delete/{id}', 'NewsController@destroy');
