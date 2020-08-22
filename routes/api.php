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

Route::post('loginReader', 'AuthController@loginReader');
Route::post('loginWorker', 'AuthController@loginWorker');
Route::post('register', 'AuthController@register');

Route::get('user/getReaders', 'UserController@getReaders');
Route::put('reader/edit/{id}', 'UserController@editReader');
Route::delete('reader/delete/{id}', 'UserController@deleteReader');

Route::get('user/getWorkers', 'UserController@getWorkers');
Route::put('worker/edit/{id}', 'UserController@editWorker');
Route::delete('worker/delete/{id}', 'UserController@deleteWorker');

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

Route::post('store', 'BookController@store');
Route::post('book/changeImage/{id}', 'BookController@changeImage');
Route::get('book/getBooks', 'BookController@getBooks');
Route::get('book/getAvailableBooks', 'BookController@getAvailableBooks');
Route::put('book/edit/{id}', 'BookController@editBook');
Route::delete('book/delete/{id}', 'BookController@deleteBook');

Route::post('borrow/addBorrow', 'BorrowController@addBorrow');
Route::put('borrow/returnBook/{id}', 'BorrowController@returnBook');
Route::get('borrow/getBorrows', 'BorrowController@getBorrows');
Route::get('borrow/history', 'BorrowController@getPastBorrows');
Route::get('borrow/getDelayed', 'BorrowController@getDelayedBorrows');

Route::group(
    ['middleware' => 'auth.jwt'], function () {
        Route::get('logout', 'AuthController@logout');
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');
    }
);

Route::group(
    ['middleware' => ['auth.jwt', 'admin']], function () {

    }
);

/*Route::group(['middleware' => ['auth.jwt', 'worker']], function () {

});*/

Route::group(
    ['middleware' => ['auth.jwt', 'admin', 'worker']], function () {

    }
);
