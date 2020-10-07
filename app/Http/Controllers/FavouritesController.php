<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FavouriteBooks;
use App\FavouriteAuthors;
use App\Book;
use App\Author;
use DB;
use Auth;

class FavouritesController extends Controller
{
    /**
     * Add author to the list of favourites.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addAuthor(Request $request)
    {
        if (FavouriteAuthors::where('user_id', '=', $request->user_id)->where('author_id', '=', $request->author_id)->count() > 0) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Record already exists.'
                ], 409
            );
        }
        
        $favAuthor = new FavouriteAuthors();
        $favAuthor->user_id = $request->user_id;
        $favAuthor->author_id = $request->author_id;

        $favAuthor->save();

        return response()->json(
            [
            'success' => true,
            'favAuthor' => $favAuthor
            ], 201
        );
    }

    /**
     * Remove the specified author from the list.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function removeAuthor($id)
    {
        $favAuthor = FavouriteAuthors::where('user_id', '=', Auth::user()->id)->get()->toArray();

        if (!$favAuthor) {
            return response()->json(
                [
                'success' => false,
                'message' => 'User does not have their favourite authors list.'
                ], 500
            );
        }

        $favAuthor = FavouriteAuthors::where('author_id', '=', $id)->delete();

        return response()->json(
            [
            'success' => true,
            'message' => 'Author from list has beed removed.'
            ]
        );
    }

    /**
     * Display the list of favourite authors for a specific user.
     *
     * @return Response
     */
    public function getFavouriteAuthors($id)
    {
         $favAuthor = DB::table('favourite_authors')
             ->where('user_id', '=', $id)
             ->join('authors', 'authors.id', '=', 'favourite_authors.author_id')
             ->select(
                'authors.name', 'authors.surname', 'authors.id'
             )
             ->get()
             ->toArray();

        return $favAuthor;
    }

    /*************************************************************************************************************/

    /**
     * Add book to the list of favourites.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addBook(Request $request)
    {
        if (FavouriteBooks::where('user_id', '=', $request->user_id)->where('book_id', '=', $request->book_id)->count() > 0) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Record already exists.'
                ], 409
            );
        }
        
        $favBook = new FavouriteBooks();
        $favBook->user_id = $request->user_id;
        $favBook->book_id = $request->book_id;

        $favBook->save();

        return response()->json(
            [
            'success' => true,
            'favBook' => $favBook
            ], 201
        );
    }

    /**
     * Remove the specified book from the list.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function removeBook($id)
    {
        $favBook = FavouriteBooks::where('user_id', '=', Auth::user()->id)->get()->toArray();

        if (!$favBook) {
            return response()->json(
                [
                'success' => false,
                'message' => 'User does not have their favourite books list.'
                ], 500
            );
        }

        $favBook = FavouriteBooks::where('book_id', '=', $id)->delete();

        return response()->json(
            [
            'success' => true,
            'message' => 'Book from list has beed removed.'
            ]
        );
    }

    /**
     * Display the list of favourite books for a specific user.
     *
     * @return Response
     */
    public function getFavouriteBooks($id)
    {
         $favBook = DB::table('favourite_books')
             ->where('user_id', '=', $id)
             ->join('books', 'books.id', '=', 'favourite_books.book_id')
             ->join('authors', 'authors.id', '=', 'books.author_id')
            ->select(
                'books.title', 'authors.name', 'authors.surname', 'books.id'
            )
             ->get()
             ->toArray();

        return $favBook;
    }
}