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

Route::post('loginReader', 'LoginController@loginReader');
Route::post('loginWorker', 'AuthController@loginWorker');
Route::post('refresh', 'AuthController@refresh');

Route::get('book/getBooks', 'BookController@getBooks');
Route::get('book/getNewBooks', 'BookController@getNewBooks');
Route::get('book/{id}', 'BookController@showBook');
Route::get('author/{id}/books', 'BookController@authorBooks');

Route::get('category/getCategories', 'CategoryController@getCategories');

Route::get('publisher/getPublishers', 'PublisherController@getPublishers');

Route::get('notifications/{id}', 'NotificationController@getNotifications');
Route::get('notifications/read/{id}', 'NotificationController@markAsRead');

Route::get('author/getAuthors', 'AuthorController@getAuthors');
Route::get('author/{id}', 'AuthorController@showAuthor');

Route::get('rating/{id}', 'RatingController@showRating');
Route::get('rating/all/{id}', 'RatingController@getRatings');

Route::post('password-reset', 'AuthController@passwordReset');
Route::post('password-change', 'AuthController@passwordChange');
Route::post('first-login-password', 'AuthController@firstLoginPassword');

Route::get('borrow/monthly', 'BorrowController@getMonthlyAmount');
Route::get('rating/monthly/get', 'RatingController@getMonthlyRating');
Route::get('reader/get/monthly', 'ReaderController@getMonthlyReaders');
Route::get('book/monthly/get/all', 'BookController@getMonthlyBooks');

Route::get('calendar/event/getEvents', 'EventController@getEvents');
Route::get('calendar/type/getTypes', 'TypeController@getTypes');

Route::group(
    ['middleware' => 'auth.jwt'], function () {
        Route::get('logout', 'AuthController@logout');
        Route::post('logout', 'AuthController@logout');
        //Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');

        Route::post('rating/add', 'RatingController@addRating');
        Route::delete('rating/delete/{id}', 'RatingController@deleteRating');
        Route::put('rating/edit/{id}', 'RatingController@editRating');

        Route::get('user/profile/{id}', 'ReaderController@showReader');
        Route::post('user/profile/upload', 'UserController@changeAvatar');

        Route::get('borrow/showBorrow/{id}', 'BorrowController@showBorrow');
        Route::get('borrow/showDelay/{id}', 'BorrowController@showDelay');
        Route::put('borrow/extend/{id}', 'BorrowController@extendDate');
        Route::get('borrow/getAmount/{id}', 'BorrowController@getAmount');
        Route::get('borrow/getAuthors/{id}', 'BorrowController@getAuthors');
        Route::get('borrow/getCategory/{id}', 'BorrowController@getCategory');

        Route::get('rating/ratingsAmount/{id}', 'RatingController@ratingsAmount');

        Route::post('follow/addAuthor', 'FollowController@addAuthor');
        Route::get('follow/getFollowedAuthors/{id}', 'FollowController@getFollowedAuthors');
        Route::delete('follow/delete/{id}/author', 'FollowController@removeAuthor');

        Route::post('suggestions/add', 'SuggestionController@addSuggestion');

        Route::delete('reader/delete/{id}', 'ReaderController@deleteReader');
    }
);

Route::group(
    ['middleware' => ['auth.jwt', 'admin']], function () {
        Route::get('user/getWorkers', 'WorkerController@getWorkers');
        Route::put('worker/edit/{id}', 'WorkerController@editWorker');
        Route::delete('worker/delete/{id}', 'WorkerController@deleteWorker');
        
        Route::get('user/getRoles', 'RoleController@getRoles');
        Route::put('roles/edit/{id}', 'RoleController@editRoles');
    }
);

Route::group(
    ['middleware' => ['auth.jwt', 'worker']], function () {
        Route::post('register', 'AuthController@register');

        Route::get('user/getReaders', 'ReaderController@getReaders');
        Route::put('reader/edit/{id}', 'ReaderController@editReader');

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
        Route::get('book/getBooks/isbn', 'BookController@getBooksISBN');
        Route::post('book/changeImage/{id}', 'BookController@changeImage');
        Route::get('book/get/availableBooks', 'BookController@getAvailableBooks');
        Route::put('book/edit/{id}', 'BookController@editBook');
        Route::put('book/edit/isbn/{id}', 'BookController@editBookISBN');
        Route::delete('book/delete/{id}', 'BookController@deleteBook');
        Route::delete('book/delete/group/{id}', 'BookController@deleteGroup');

        Route::post('borrow/addBorrow', 'BorrowController@addBorrow');
        Route::put('borrow/returnBook/{id}', 'BorrowController@returnBook');
        Route::get('borrow/getBorrows', 'BorrowController@getBorrows');
        Route::get('borrow/history', 'BorrowController@getPastBorrows');
        Route::get('borrow/getDelayed', 'BorrowController@getDelayedBorrows');
        Route::put('borrow/edit/{id}', 'BorrowController@editBorrow');

        Route::put('calendar/type/edit/{id}', 'TypeController@editType');
        Route::post('calendar/type/add', 'TypeController@addType');
        Route::delete('calendar/type/delete/{id}', 'TypeController@deleteType');

        Route::put('calendar/event/edit/{id}', 'EventController@editEvent');
        Route::post('calendar/event/add', 'EventController@addEvent');
        Route::delete('calendar/event/delete/{id}', 'EventController@deleteEvent');

        Route::get('suggestions/getSuggestions', 'SuggestionController@getSuggestions');
        Route::delete('suggestions/delete/{id}', 'SuggestionController@deleteSuggestion');
    }
);
