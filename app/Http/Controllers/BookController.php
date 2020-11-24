<?php

namespace App\Http\Controllers;

use App\Book;
use App\Category;
use App\Author;
use App\Publisher;
use App\Rating;
use App\Http\Requests\StoreBook;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use DB;
use Carbon\Carbon;

class BookController extends Controller
{
    /**
     * Display a listing of books (listing by groups).
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
            ->groupBy('books.description')
            ->get()
            ->toArray();

        return $data;
    }

    /**
     * Display a listing of books with ISBN (every single book listed).
     *
     * @return Response
     */
    public function getBooksISBN()
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
            ->groupBy('books.isbn')
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
                DB::raw('COUNT(books.description) as amount'), 'categories.name as categoryName', 'authors.name as authorName',
                'authors.surname', 'authors.id as authorID', 'publishers.name as publisherName', 'books.cover'
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
     * Display book for a specific author.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function authorBooks($id)
    {
        $book = DB::table('books')
            ->where('books.author_id', '=', $id)
            ->join('categories', 'categories.id', '=', 'books.category_id')
            ->join('publishers', 'publishers.id', '=', 'books.publisher_id')
            ->select(
                'books.id', 'books.title', 'books.description', 'books.publish_year',
                'categories.name as categoryName', 'publishers.name as publisherName', 'books.cover'
            )
            ->groupBy('books.description')
            ->get()
            ->toArray();

        if (!$book) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, author does not have any books.',
                ], 400
            );
        }

        return $book;
    }

    /**
     * Display a listing of books from the last 10 days.
     *
     * @return Response
     */
    public function getNewBooks()
    {
        $date = Carbon::today()->subDays(7);

        $data = DB::table('books')
            ->where('books.created_at', '>=', $date)
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
     * Store a newly created book.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(StoreBook $request) //create StoreBook
    {
        $isbn = explode("\r", $request->get('isbn'));
        $isbn = preg_replace("/\r|\n/", "", $isbn);
        $amount = count($isbn);

        for($index = 0; $index < $amount; $index++) {
            $book = new Book();
            $book->title = $request->get('title');
            $book->description = $request->get('description');
            $book->publish_year = $request->get('publish_year');
            $book->isbn = $isbn[$index];
            $book->author_id = $request->get('author');
            $book->category_id = $request->get('category');
            $book->publisher_id = $request->get('publisher');
            $book->amount = $amount;

            if ($file = $request->hasFile('cover')) {
                $book->cover = $imagePath = $request->file('cover')->store('books', 'public');

                $cover = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
                $cover->save();

                $imageArray = ['cover' => $imagePath];
            }

            $book->save();
        }
        
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
    public function changeImage(Request $request, $id)
    {
        $desc = Book::where('id', '=', $id)->pluck('description');
        $book = Book::where('description', '=', $desc)->get();

        if (!$book) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, book cannot be found.'
                ], 400
            );
        }

        for($index = 0; $index < count($book); $index++) {
            if ($file = $request->hasFile('cover')) {
                $book[$index]->cover = $imagePath = $request->file('cover')->store('books', 'public');

                $cover = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
                $cover->save();

                $imageArray = ['cover' => $imagePath];

                $book[$index]->save();
            }
        }

        if ($book[count($book)-1]->save()) {
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
                'message' => 'Sorry, cover could not be changed.',
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
        $desc = Book::where('id', '=', $id)->pluck('description');
        $book = Book::where('description', '=', $desc)->get();

        if (!$book) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, book cannot be found.'
                ], 400
            );
        }

        for($index = 0; $index < count($book); $index++) {
            $book[$index]->title = $request->title;
            $book[$index]->description = $request->description;
            $book[$index]->publish_year = $request->publish_year;

            $book[$index]->save();
        }

        if ($book[count($book)-1]->save()) {
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
     * Edit specific book (ISBN).
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editBookISBN(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, book cannot be found.'
                ], 400
            );
        }

        $book->isbn = $request->isbn;

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
        $amount = (int)$book->where('id', '=', $id)->first()->amount;
        $amount = $amount - 1;
        $desc = $book->where('id', '=', $id)->pluck('description');

        if (!$book) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, book with id ' . $id . ' cannot be found.'
                ], 400
            );
        }

        $duplicates = Book::where('description', '=', $desc)->skip(1)->take(1)->get('id');

        if (!$duplicates) {
            return response()->json(
                [
                'success' => true,
                'message' => 'The last book of this collection was deleted (with ratings).'
                ]
            );
        }

        $rating = Rating::where('book_id', '=', $id)->update(['book_id' => $duplicates[0]->id]);

        $book = Book::where('description', '=', $desc)->update(['amount' => $amount]);
        $book = Book::find($id);

        if ($book->destroy($id)) {
            return response()->json(
                [
                'success' => true,
                'message' => 'Book was deleted.'
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
     * Remove the specified group of books.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteGroup($id)
    {
        $desc = Book::where('id', '=', $id)->pluck('description');
        $book = Book::where('description', '=', $desc)->get();
        $bookID = $book->pluck('id');
        
        if (!$book) {
            return response()->json(
                [
                'success' => false,
                'message' => 'Sorry, book cannot be found.'
                ], 400
            );
        }
        
        for($index = 0; $index < count($bookID); $index++) {
            $book = Book::find($bookID[$index])->destroy($bookID[$index]);
        }

        return response()->json(
            [
            'success' => true,
            'message' => 'Group of books was deleted.',
            ]
        );
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
