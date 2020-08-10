<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class BookController extends Controller
{
    /**
     * Display a listing of books.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $books = Book::query();

        if ($request->has('sort') && $request->get('sort') === 'date_desc') {
            $books->orderBy('created_at', 'desc');
        }

        if ($request->has('query')) {
            $query = $request->get('query');
            $books->where('author', 'like', '%' . $query . '%')
                ->orWhere('title', 'like', '%' . $query . '%');
        }

        if ($request->has('category')) {
            $category = $request->get('category');
            $books->whereHas('categories', function ($q) use ($category) {
                $q->where('category_id', $category);
            });
        }
        return $books->paginate(6, ['id', 'title', 'description', 'publish_year', 'cover'])->appends($request->input());
    }

    /**
     * Display book by id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $book = Book::with('author:id,name,surname')->find($id); //dodać wydawnictwo

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, book with id ' . $id . ' cannot be found.',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'book' => $book,
        ]);
    }

    /**
     * Store a newly created book.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(StoreBook $request) //create StoreBook
    {
        $book = new Book();
        $book->isbn = $request->isbn;
        $book->title = $request->title;
        $book->description = $request->description;
        $book->publish_year = $request->publish_year;

        if (request('image')) {
            $book->cover = $imagePath = request('image')->store('books', 'public');

            $cover = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
            $cover->save();

            $imageArray = ['image' => $imagePath];
        }

        if (auth()->user()->books()->save($book)) {
            $book->categories()->attach($request->categories);
            return response()->json([
                'success' => true,
                'book' => $book
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, book could not be added.',
            ], 500);
        }
    }

    /**
     * Update the specified book.
     *
     * @param Request $request
     * @param Book $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Book $book)
    {
        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, book cannot be found.',
            ], 400);
        }

        $updated = $book->update($request->only(['isbn', 'title', 'description', 'publish_year']));

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Book has been updated',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, book could not be updated.',
            ], 500);
        }
    }

    /**
     * Remove the specified book.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, book with id ' . $id . ' cannot be found.',
            ], 400);
        }

        if ($book->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Book was successfully removed',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Book could not be deleted.',
            ], 500);
        }
    }
}
