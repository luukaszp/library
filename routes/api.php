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
Route::put('reader/edit/{id}', 'UserController@editReader');
Route::delete('reader/delete/{id}', 'UserController@deleteReader');

Route::get('user/getWorkers', 'UserController@getWorkers');

Route::get('user/getRoles', 'UserController@getRoles');

Route::get('category/getCategories', 'CategoryController@getCategories');
Route::put('category/edit/{id}', 'CategoryController@editCategory');
Route::post('category/add', 'CategoryController@addCategory');
Route::delete('category/delete/{id}', 'CategoryController@deleteCategory');

Route::get('publisher/getPublishers', 'PublisherController@getPublishers');
Route::put('publisher/edit/{id}', 'PublisherController@editPublisher');
Route::post('publisher/add', 'PublisherController@addPublisher');
Route::delete('publisher/delete/{id}', 'PublisherController@deletePublisher');

Route::get('author/getAuthors', 'AuthorController@getAuthors');
Route::put('author/edit/{id}', 'AuthorController@editAuthor');
Route::post('author/add', 'AuthorController@addAuthor');
Route::delete('author/delete/{id}', 'AuthorController@deleteAuthor');

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