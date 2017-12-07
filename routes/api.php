<?php

use Illuminate\Http\Request;

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

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('profile', 'Api\UserController@profile');
    Route::get('users', 'Api\UserController@index');
    Route::get('user/{user}', 'Api\UserController@show');
    Route::post('user/{user}/update', 'Api\UserController@update');
});