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

Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@register');

Route::get('user/getReaders', 'UserController@getReaders');

Route::get('user/getWorkers', 'UserController@getWorkers');

Route::get('user/getRoles', 'UserController@getRoles');

Route::get('category/getCategories', 'CategoryController@getCategories');
Route::put('category/edit/{id}', 'CategoryController@editCategory');
Route::post('category/add', 'CategoryController@addCategory');
Route::delete('category/delete/{id}', 'CategoryController@deleteCategory');

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('logout', 'AuthController@logout');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::group(['middleware' => ['auth.jwt', 'admin']], function () {

});

/*Route::group(['middleware' => ['auth.jwt', 'worker']], function () {

});*/

Route::group(['middleware' => ['auth.jwt', 'admin', 'worker']], function () {

});