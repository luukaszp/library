<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rating;
use App\Book;

class RatingController extends Controller
{
    /**
     * Display specified rating
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = auth()->user();
        $rates = Rating::where('book_id', $id);
        $data = [
            'avg' => (float) $rates->avg('rate'),
            'count' => $rates->count('rate'),
            'voted' => false,
        ];
        if ($user) {
            $data['voted'] = $rates->where('user_id', $user->id)->count() > 0;
        }
        return response()->json([
            'success' => true,
            'ratings' => $data,
        ]);
    }

    /**
     * Store a newly created rating.
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'rate' => 'required',
        ]);

        $book = Book::find($request->id);

        $rating = new Rating();
        $rating->rate = $request->rate;
        $rating->user_id = auth()->user()->id;
        $rating->book_id = $book->id;

        if ($book->ratings()->save($rating)) {
            return response()->json([
                'success' => true,
                'rating' => $rating,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, this book could not be rated.',
            ], 500);
        }
    }
}
