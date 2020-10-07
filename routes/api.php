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
Route::post('refresh', 'AuthController@refresh');

Route::get('book/getBooks', 'BookController@getBooks');
Route::get('book/{id}', 'BookController@showBook');

Route::get('category/getCategories', 'CategoryController@getCategories');

Route::get('publisher/getPublishers', 'PublisherController@getPublishers');

Route::get('author/getAuthors', 'AuthorController@getAuthors');

Route::get('rating/{id}', 'RatingController@showRating');
Route::get('rating/all/{id}', 'RatingController@getRatings');

Route::get('opinion/all/{id}', 'OpinionController@getOpinions');

Route::group(
    ['middleware' => 'auth.jwt'], function () {
        Route::get('logout', 'AuthController@logout');
        Route::post('logout', 'AuthController@logout');
        //Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');

        Route::post('opinion/add', 'OpinionController@addOpinion');
        Route::put('opinion/edit/{id}', 'OpinionController@editOpinion');

        Route::post('rating/add', 'RatingController@addRating');
        Route::delete('rating/delete/{id}', 'RatingController@deleteRating');
    }
);

Route::group(
    ['middleware' => ['auth.jwt', 'admin']], function () {
        Route::get('user/getWorkers', 'UserController@getWorkers');
        Route::put('worker/edit/{id}', 'UserController@editWorker');
        Route::delete('worker/delete/{id}', 'UserController@deleteWorker');
        
        Route::get('user/getRoles', 'UserController@getRoles');
        Route::put('roles/edit/{id}', 'UserController@editRoles');
    }
);

Route::group(
    ['middleware' => ['auth.jwt', 'worker']], function () {
        Route::post('register', 'AuthController@register');

        Route::get('user/getReaders', 'UserController@getReaders');
        Route::put('reader/edit/{id}', 'UserController@editReader');
        Route::delete('reader/delete/{id}', 'UserController@deleteReader');

        Route::put('category/edit/{id}', 'CategoryController@editCategory');
        Route::post('category/add', 'CategoryController@addCategory');
        Route::delete('category/delete/{id}', 'CategoryController@deleteCategory');

        Route::put('publisher/edit/{id}', 'PublisherController@editPublisher');
        Route::post('publisher/add', 'PublisherController@addPublisher');
        Route::delete('publisher/delete/{id}', 'PublisherController@deletePublisher');

        Route::put('author/edit/{id}', 'AuthorController@editAuthor');
        Route::post('author/add', 'AuthorController@addAuthor');
        Route::delete('author/delete/{id}', 'AuthorController@deleteAuthor');
        Route::post('author/changePhoto/{id}', 'AuthorController@changePhoto');

        Route::post('book/store', 'BookController@store');
        Route::post('book/changeImage/{id}', 'BookController@changeImage');
        Route::get('book/getAvailableBooks', 'BookController@getAvailableBooks');
        Route::put('book/edit/{id}', 'BookController@editBook');
        Route::delete('book/delete/{id}', 'BookController@deleteBook');

        Route::post('borrow/addBorrow', 'BorrowController@addBorrow');
        Route::put('borrow/returnBook/{id}', 'BorrowController@returnBook');
        Route::get('borrow/getBorrows', 'BorrowController@getBorrows');
        Route::get('borrow/history', 'BorrowController@getPastBorrows');
        Route::get('borrow/getDelayed', 'BorrowController@getDelayedBorrows');
        Route::put('borrow/edit/{id}', 'BorrowController@editBorrow');

        Route::get('calendar/type/getTypes', 'TypeController@getTypes');
        Route::put('calendar/type/edit/{id}', 'TypeController@editType');
        Route::post('calendar/type/add', 'TypeController@addType');
        Route::delete('calendar/type/delete/{id}', 'TypeController@deleteType');

        Route::get('calendar/event/getEvents', 'EventController@getEvents');
        Route::put('calendar/event/edit/{id}', 'EventController@editEvent');
        Route::post('calendar/event/add', 'EventController@addEvent');
        Route::delete('calendar/event/delete/{id}', 'EventController@deleteEvent');
    }
);
