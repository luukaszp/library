<?php

namespace App\Http\Controllers;

use App\Book;
use App\Category;
use App\Author;
use App\Publisher;
use App\Rating;
use App\User;
use App\Reader;
use App\Http\Requests\StoreBook;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use DB;
use Carbon\Carbon;
use AWS;

class BookController extends Controller
{
    /**
     * Get amount of books.
     *
     * @return Response
     */
    public function getAmount($id)
    {
        $getAmount = DB::table('books')
            ->select('books.*', DB::raw('COUNT(books.title) as amount'))
            ->groupBy('books.title')
            ->orderBy('amount')
            ->get();

        if(!$id) {
            return $getAmount->pluck('amount');
        }

        $count = $getAmount->where('id', '=', $id)->pluck('amount')->first();

        return $count;
    }

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
            ->join('author_book', 'author_book.book_id', '=', 'books.id')
            ->join('authors', 'authors.id', '=', 'author_book.author_id')
            ->select(
                'books.id', 'books.title', 'books.description', 'books.publish_year',
                'categories.name as categoryName', 'authors.name as authorName',
                DB::raw('COUNT(distinct author_book.book_id) as amount'), 'authors.surname', 'publishers.name as publisherName', 'books.cover'
            )
            ->distinct('books.title')
            ->groupBy('books.id', 'books.title', 'authors.name', 'authors.surname', 'publishers.name')
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
        $data = Book::with('authors')->get();
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
            ->where('books.is_available', '=', '1')
            ->select(
                'books.id', 'books.title', 'books.isbn'
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
        $amount = $this->getAmount($id);

        $author = Book::find($id)->authors()->select('id', 'name', 'surname')->get();

        $book = Book::with(['publishers:name,id', 'categories:name,id'])->find($id);

        $book['amount'] = $amount;
        $book['authors'] = $author;

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
            ->join('author_book', 'author_book.book_id', '=', 'books.id')
            ->join('authors', 'authors.id', '=', 'author_book.author_id')
            ->where('author_book.author_id', '=', $id)
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
            ->join('author_book', 'author_book.book_id', '=', 'books.id')
            ->join('authors', 'authors.id', '=', 'author_book.author_id')
            ->select(
                'books.id', 'books.title', 'categories.name as categoryName', 'authors.name as authorName',
                'authors.surname', 'books.cover'
            )
            ->distinct('books.title')
            ->groupBy('books.title', 'books.id', 'categories.name', 'authors.name', 'authors.surname', 'books.cover')
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

        $authors = explode(",", $request->get('author'));
        $authorsNumber = count($authors);
        $authorName = [];

        if ($request->hasFile('cover')) {
            $uploadedImage = $request->file('cover');
            $imageName = time() . '.' . $uploadedImage->getClientOriginalExtension();
            $destinationPath = public_path('images/covers/');
            $uploadedImage->move($destinationPath, $imageName);
            $imagePath = $destinationPath . $imageName;

            $s3 = AWS::createClient('s3');
            $s3->putObject(array(
                'Bucket'     => 'library-site',
                'Key'        => 'covers/'.$imageName,
                'SourceFile' => $imagePath,
                'ACL'        => 'public-read',
            ));
        }

        for($index = 0; $index < $amount; $index++) {
            $book = new Book();
            $book->title = $request->get('title');
            $book->description = $request->get('description');
            $book->publish_year = $request->get('publish_year');
            $book->isbn = $isbn[$index];
            $book->category_id = $request->get('category');
            $book->publisher_id = $request->get('publisher');
            $book->cover = $imageName;
            $book->save();
        }

            for($i = 0; $i < $authorsNumber; $i++) {
                $author = Author::find($authors[$i]);
                $book->authors()->attach($author);
            }

        for($i = 0; $i < $authorsNumber; $i++) {
            $authorName[$i] = Author::where('id', $authors[$i])->select('name', 'surname')->get()->first();
        }

        $details = [
            'message' => 'Nowa pozycja',
            'title' => $book->title,
            'author' => $authorName,
            'id' => $book->id
        ];

        $data = DB::table('readers')
            ->join('author_reader', 'author_reader.reader_id', '=', 'readers.id')
            ->join('authors', 'authors.id', '=', 'author_reader.author_id')
            ->select(
                'authors.name', 'authors.surname', 'readers.id'
            )
            ->get()
            ->toArray();

        $readerID = [];

        for($i = 0; $i < count($data); $i++) {
            for($j = 0; $j < count($authorName); $j++) {
                if($data[$i]->name === $authorName[$j]->name && $data[$i]->surname === $authorName[$j]->surname) {
                    $readerID[$i] = $data[$i]->id;
                }
            }
        }

        $user = [];

        for($i = 0; $i < count($readerID); $i++) {
            $reader = Reader::where('id', $readerID[$i])->get('user_id');
            $user = User::find($reader);
            $user[0]->notify(new \App\Notifications\BookAdded($details));
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

        if ($request->hasFile('cover')) {
            $uploadedImage = $request->file('cover');
            $imageName = time() . '.' . $uploadedImage->getClientOriginalExtension();
            $destinationPath = public_path('images/covers/');
            $uploadedImage->move($destinationPath, $imageName);
            $imagePath = $destinationPath . $imageName;

            $s3 = AWS::createClient('s3');
            $s3->putObject(array(
                'Bucket'     => 'library-site',
                'Key'        => 'covers/'.$imageName,
                'SourceFile' => $imagePath,
                'ACL'        => 'public-read',
            ));
        }

        if ($imagePath !== null) {
            for($index = 0; $index < count($book); $index++) {
                $book[$index]->cover = $imageName;
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

        if ($duplicates->first() === null) {
            $book->destroy($id);
            return response()->json(
                [
                'success' => true,
                'message' => 'The last book of this collection was deleted (with ratings).'
                ]
            );
        }

        $rating = Rating::where('book_id', '=', $id)->update(['book_id' => $duplicates[0]->id]);

        $book = Book::find($id);
        $book->authors()->detach($book);

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

        $updated = $book->update($request->only(['isbn', 'title', 'description', 'publish_year']));

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

    /**
     * Get amount of books that were added in current month
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getMonthlyBooks()
    {
        $firstDay = Carbon::now()->startOfMonth()->toDateString(); //current month
        $lastDay = Carbon::now()->endOfMonth()->toDateString();

        $currentMonth = Book::whereBetween('books.created_at', [$firstDay, $lastDay])->count('id');

        return $currentMonth;
    }
}
