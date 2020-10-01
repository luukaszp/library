<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FavouriteBooks;
use App\FavouriteAuthors;

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
     * @param  FavouriteAuthors $favAuthor
     * @param  Author           $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function removeAuthor(FavouriteAuthors $favAuthor, $id)
    {
        $author = Author::find($id);

        if ($favAuthor->authors()->detach($author)) {
            return response()->json(
                [
                'success' => true,
                'message' => 'Author from given list was successfully removed.'
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Author from given list could not be deleted.'
                ], 500
            );
        }
    }

    /**
     * Display the list of favourite authors for a specific user.
     *
     * @return Response
     */
    public function getFavouriteAuthors($user_id)
    {
        $favAuthor = FavouriteAuthors::where('user_id', '=', $id)->get(['id'])->toArray();
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
     * @param  FavouriteBooks $favBook
     * @param  Book           $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function removeBook(FavouriteBooks $favBook, $id)
    {
        $book = Book::find($id);

        if ($favBook->books()->detach($book)) {
            return response()->json(
                [
                'success' => true,
                'message' => 'Book from given list was successfully removed.'
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Book from given list could not be deleted.'
                ], 500
            );
        }
    }

    /**
     * Display the list of favourite books for a specific user.
     *
     * @return Response
     */
    public function getFavouriteBooks($user_id)
    {
        $favBook = FavouriteBooks::where('user_id', '=', $id)->get(['id'])->toArray();
        return $favBook;
    }
}
