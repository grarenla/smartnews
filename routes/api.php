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
//API news
Route::get('/news', 'NewsController@index'); //get list news
Route::post('/news', 'NewsController@store'); // create a record news
//Route::get('/news/{id}', 'NewsController@showId'); // get news by Id
Route::get('/news/{url}', 'NewsController@showUrl'); // get news by Url
Route::put('/news/{id}', 'NewsController@update'); // edit news
Route::delete('/news/{id}', 'NewsController@destroy'); // delete news
//Route::get('/category/news/{id}', 'NewsController@listByCategoryId'); //get list news by category_id
Route::get('/category/news/{url}', 'NewsController@listByCategoryUrl'); //get list news by category_id

//API category
Route::get('/category', 'CategoryController@index'); // list category

// Auth
Route::post('register', 'ApiAuthController@register'); // register
Route::post('login', 'ApiAuthController@login'); // login(return token)


Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('auth', 'ApiAuthController@user'); // get info user
});

Route::post('fb-google', 'ApiAuthController@registerOrLogin');
//Route::middleware('cors')->get('/news', 'NewsController@index');

//Route::middleware('cors')->get('/news/{id}', 'NewsController@show');
//Route::middleware('cors')->get('/category/news/{id}', 'NewsController@listByCategory');
//Route::middleware('cors')->post('/news/insert', 'NewsController@store');
//Route::middleware('cors')->put('/news/edit/{id}', 'NewsController@update');
//Route::middleware('cors')->delete('/news/delete/{id}', 'NewsController@destroy');
