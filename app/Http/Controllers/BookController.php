<?php

namespace App\Http\Controllers;

use App\Book;
use App\Category;
use App\Author;
use App\Publisher;
use App\Http\Requests\StoreBook;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use DB;

class BookController extends Controller
{
    /**
     * Display a listing of books.
     *
     * @return Response
     */
    public function getBooks()
    {
        $data = DB::table('books')
            ->join('categories', 'categories.id', '=', 'books.category_id')
            ->join('publishers', 'publishers.id', '=', 'books.publisher_id')
            ->join('authors', 'authors.id', '=', 'books.author_id')
            ->select(
                'books.id', 'books.title', 'books.isbn', 'books.description', 'books.publish_year',
                'books.amount', 'categories.name as categoryName', 'authors.name as authorName',
                'authors.surname', 'publishers.name as publisherName', 'books.cover'
            )
            ->get()
            ->toArray();
        return $data;
    }

    /**
     * Display a listing of available books.
     *
     * @return Response
     */
    public function getAvailableBooks()
    {
        $data = DB::table('books')
            ->where('books.amount', '>', '0')
            ->join('categories', 'categories.id', '=', 'books.category_id')
            ->join('publishers', 'publishers.id', '=', 'books.publisher_id')
            ->join('authors', 'authors.id', '=', 'books.author_id')
            ->select(
                'books.id', 'books.title', 'books.isbn', 'books.description', 'books.publish_year',
                'books.amount', 'categories.name as categoryName', 'authors.name as authorName',
                'authors.surname', 'publishers.name as publisherName', 'books.cover'
            )
            ->get()
            ->toArray();
        return $data;
    }

    /**
     * Display book by id
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showBook($id)
    {
        $book = DB::table('books')
            ->where('books.id', '=', $id)
            ->join('categories', 'categories.id', '=', 'books.category_id')
            ->join('publishers', 'publishers.id', '=', 'books.publisher_id')
            ->join('authors', 'authors.id', '=', 'books.author_id')
            ->select(
                'books.id', 'books.title', 'books.isbn', 'books.description', 'books.publish_year',
                'books.amount', 'categories.name as categoryName', 'authors.name as authorName',
                'authors.surname', 'publishers.name as publisherName', 'books.cover'
            )
            ->get()
            ->toArray();

        if (!$book) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, book with id ' . $id . ' cannot be found.',
                ], 400
            );
        }

        return $book;
    }

    /**
     * Store a newly created book.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(StoreBook $request) //create StoreBook
    {
        $book = new Book();
        $book->title = $request->get('title');
        $book->isbn = $request->get('isbn');
        $book->description = $request->get('description');
        $book->publish_year = $request->get('publish_year');
        $book->amount = $request->get('amount');

        $book->author_id = $request->get('author');
        $book->category_id = $request->get('category');
        $book->publisher_id = $request->get('publisher');

        if ($file = $request->hasFile('cover')) {
            $book->cover = $imagePath = $request->file('cover')->store('books', 'public');

            $cover = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
            $cover->save();

            $imageArray = ['cover' => $imagePath];
        }

        //if (auth()->user()->books()->save($book)) { Tutaj użytkownik zalogowany
        if ($book->save()) {
            return response()->json(
                [
                'success' => true,
                'book' => $book
                ], 201
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, book could not be added.',
                ], 500
            );
        }
    }

    /**
     * Edit current book cover
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function changeImage(Request $request, $id) //create StoreBook
    {
        $book = Book::find($id);

        if ($file = $request->hasFile('cover')) {
            $book->cover = $imagePath = $request->file('cover')->store('books', 'public');

            $cover = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
            $cover->save();

            $imageArray = ['cover' => $imagePath];
        }

        //if (auth()->user()->books()->save($book)) { Tutaj użytkownik zalogowany
        if ($book->save()) {
            return response()->json(
                [
                'success' => true,
                'book' => $book
                ], 201
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, book could not be added.',
                ], 500
            );
        }
    }

    /**
     * Edit specific book.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editBook(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, book with id ' . $id . ' cannot be found.'
                ], 400
            );
        }

        $book->title = $request->title;
        $book->isbn = $request->isbn;
        $book->description = $request->description;
        $book->publish_year = $request->publish_year;
        $book->amount = $request->amount;
        $book->save();

        if ($book->save()) {
            return response()->json(
                [
                'success' => true,
                'message' => 'Book has been updated',
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, book could not be updated.',
                ], 500
            );
        }
    }

    /**
     * Remove the specified book.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBook($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, book with id ' . $id . ' cannot be found.'
                ], 400
            );
        }

        if ($book->destroy($id)) {
            return response()->json(
                [
                'success' => true
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Book could not be deleted.'
                ], 500
            );
        }
    }

    /**
     * Update the specified book.
     *
     * @param  Request $request
     * @param  Book    $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Book $book)
    {
        if (!$book) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, book cannot be found.',
                ], 400
            );
        }

        $updated = $book->update($request->only(['isbn', 'title', 'description', 'publish_year', 'amount']));

        if ($updated) {
            return response()->json(
                [
                'success' => true,
                'message' => 'Book has been updated',
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, book could not be updated.',
                ], 500
            );
        }
    }

    /**
     * Remove the specified book.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, book with id ' . $id . ' cannot be found.',
                ], 400
            );
        }

        if ($book->delete()) {
            return response()->json(
                [
                'success' => true,
                'message' => 'Book was successfully removed',
                ]
            );
        } else {
            return response()->json(
                [
                'success' => false,
                'message' => 'Book could not be deleted.',
                ], 500
            );
        }
    }
}
